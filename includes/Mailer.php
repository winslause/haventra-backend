<?php
// includes/Mailer.php - Lightweight SMTP mailer using stream sockets (TLS)
// No external dependencies required. Uses PHP's built-in stream_socket_client + OpenSSL.

class Mailer {
    private $host;
    private $port;
    private $username;
    private $password;
    private $fromName;
    private $fromEmail;
    private $smtp;
    private $error = '';
    private $useSsl = false;

    public function __construct($host, $port, $username, $password, $fromName = 'HAVENTRA', $fromEmail = null) {
        $this->host = $host;
        $this->port = $port;
        $this->username = $username;
        $this->password = $password;
        $this->fromName = $fromName;
        $this->fromEmail = $fromEmail ?: $username;
        // Port 465 = SSL wrapper, Port 587 = STARTTLS
        $this->useSsl = ($port == 465);
    }

    public function send($to, $subject, $bodyHtml, $bodyText = null, $replyTo = null, $replyToName = null) {
        try {
            $this->connect();
            $this->hello();
            if (!$this->useSsl) {
                $this->startTls();
            }
            $this->auth();
            $this->mailFrom();
            $this->rcptTo($to);
            $this->data($subject, $bodyHtml, $bodyText, $replyTo, $replyToName);
            $this->quit();
            return true;
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    public function getError() {
        return $this->error;
    }

    /* ---------- Low-level SMTP ---------- */

    private function connect() {
        $opts = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            ],
        ];
        $ctx = stream_context_create($opts);
        $protocol = $this->useSsl ? 'ssl' : 'tls';
        $addr = "{$protocol}://{$this->host}:{$this->port}";
        $this->smtp = stream_socket_client($addr, $errno, $errstr, 10, STREAM_CLIENT_CONNECT, $ctx);
        if (!$this->smtp) {
            throw new Exception("Could not connect to SMTP server: {$errstr} ({$errno})");
        }
        stream_set_timeout($this->smtp, 10);
        $this->readResponse(); // 220 greeting (always read it)
    }

    private function hello() {
        $this->sendCommand("EHLO localhost");
        $this->expectCode(250);
    }

    private function startTls() {
        $this->sendCommand("STARTTLS");
        $this->expectCode(220);
        // Re-enable crypto on existing stream
        if (!stream_socket_enable_crypto($this->smtp, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
            throw new Exception("Could not enable TLS encryption");
        }
    }

    private function auth() {
        $this->sendCommand("AUTH LOGIN");
        $this->expectCode(334);

        $this->sendCommand(base64_encode($this->username));
        $this->expectCode(334);

        $this->sendCommand(base64_encode($this->password));
        $this->expectCode(235);
    }

    private function mailFrom() {
        $this->sendCommand("MAIL FROM:<{$this->fromEmail}>");
        $this->expectCode(250);
    }

    private function rcptTo($to) {
        $this->sendCommand("RCPT TO:<{$to}>");
        $this->expectCode(250);
    }

    private function data($subject, $bodyHtml, $bodyText, $replyTo, $replyToName) {
        $this->sendCommand("DATA");
        $this->expectCode(354);

        $boundary = "----=_HAVENTRA_" . md5(uniqid(rand(), true));
        $headers = [];
        $headers[] = "From: {$this->fromName} <{$this->fromEmail}>";
        if ($replyTo) {
            $headers[] = "Reply-To: {$replyToName} <{$replyTo}>";
        }
        $headers[] = "MIME-Version: 1.0";
        $headers[] = "Content-Type: multipart/alternative; boundary=\"{$boundary}\"";
        $headers[] = "Subject: {$subject}";
        $headers[] = "X-Mailer: HAVENTRA/1.0";

        $message = implode("\r\n", $headers) . "\r\n\r\n";

        if ($bodyText) {
            $message .= "--{$boundary}\r\n";
            $message .= "Content-Type: text/plain; charset=UTF-8\r\n";
            $message .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
            $message .= $bodyText . "\r\n\r\n";
        }

        $message .= "--{$boundary}\r\n";
        $message .= "Content-Type: text/html; charset=UTF-8\r\n";
        $message .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
        $message .= $bodyHtml . "\r\n\r\n";
        $message .= "--{$boundary}--\r\n";

        $this->fwrite($message . "\r\n.");
        $this->expectCode(250);
    }

    private function quit() {
        @$this->sendCommand("QUIT");
        @fclose($this->smtp);
    }

    private function sendCommand($cmd) {
        $this->fwrite($cmd . "\r\n");
    }

    private function fwrite($data) {
        $result = @fwrite($this->smtp, $data);
        if ($result === false) {
            throw new Exception("Failed to write to SMTP stream");
        }
    }

    private function readResponse() {
        $response = '';
        while ($line = fgets($this->smtp, 515)) {
            $response .= $line;
            if (substr($line, 3, 1) === ' ') {
                break;
            }
        }
        return trim($response);
    }

    private function expectCode($expected) {
        $response = $this->readResponse();
        $code = (int) substr($response, 0, 3);
        if ($code !== $expected) {
            throw new Exception("SMTP error: expected {$expected}, got {$response}");
        }
        return $response;
    }

    public function __destruct() {
        if (is_resource($this->smtp)) {
            @fclose($this->smtp);
        }
    }
}
