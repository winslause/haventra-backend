<!-- ================= CONTACT & HELP SECTION ================= -->
<section class="relative contact-bg py-16 md:py-24 px-4 md:px-6 overflow-hidden">

    <div class="relative max-w-6xl mx-auto">

        <!-- Heading -->
        <div class="text-center mb-12 md:mb-16">
            <span class="inline-block bg-accentOrange/10 text-accentOrange text-xs font-bold uppercase tracking-widest px-4 py-1.5 rounded-full mb-4">
                We're here for you
            </span>
            <h1 class="font-serif text-4xl md:text-6xl text-navy font-semibold mb-4 leading-tight">
                Contact &amp; <span class="text-accentBlue">Help Center</span>
            </h1>
            <p class="text-slate-500 text-sm md:text-base max-w-2xl mx-auto leading-relaxed">
                Have a question about a property, need help with your listing, or just want to say hello?
                Send us a message and our team will get back to you within 24 hours.
            </p>
        </div>

        <!-- Main Grid: Form (left) + Info cards (right) -->
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 md:gap-8">

            <!-- ===== FORM (60%) ===== -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-100 p-6 md:p-10 relative overflow-hidden">

                    <!-- Decorative corner accent -->
                    <div class="absolute -top-16 -right-16 w-48 h-48 bg-gradient-to-br from-accentOrange/10 to-accentBlue/10 rounded-full blur-2xl pointer-events-none"></div>

                    <div class="relative">
                        <h2 class="font-serif text-2xl md:text-3xl text-navy font-semibold mb-1">Send us a message</h2>
                        <p class="text-slate-400 text-xs md:text-sm mb-6">Fill in the form below and we'll be in touch.</p>

                        <form id="contactForm" class="space-y-4 md:space-y-5" onsubmit="handleContactSubmit(event)">

                            <!-- Name + Email row -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 md:gap-5">
                                <div>
                                    <label for="cName" class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">
                                        Full Name <span class="text-red-400">*</span>
                                    </label>
                                    <div class="relative">
                                        <i class="fas fa-user absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-300 text-sm"></i>
                                        <input type="text" id="cName" required placeholder="Kaylor"
                                               class="form-input w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 placeholder-slate-400">
                                    </div>
                                </div>
                                <div>
                                    <label for="cEmail" class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">
                                        Email Address <span class="text-red-400">*</span>
                                    </label>
                                    <div class="relative">
                                        <i class="fas fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-300 text-sm"></i>
                                        <input type="email" id="cEmail" required placeholder="Kaylor@example.com"
                                               class="form-input w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 placeholder-slate-400">
                                    </div>
                                </div>
                            </div>

                            <!-- Subject -->
                            <div>
                                <label for="cSubject" class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">
                                    Subject
                                </label>
                                <div class="relative">
                                    <i class="fas fa-tag absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-300 text-sm"></i>
                                    <select id="cSubject"
                                            class="form-input w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 appearance-none cursor-pointer">
                                        <option value="">Choose a topic…</option>
                                        <option value="general">General Inquiry</option>
                                        <option value="property">Property Question</option>
                                        <option value="listing">Listing & Selling</option>
                                        <option value="booking">Booking & Short Stay</option>
                                        <option value="technical">Technical Support</option>
                                        <option value="other">Other</option>
                                    </select>
                                    <i class="fas fa-chevron-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-300 text-xs pointer-events-none"></i>
                                </div>
                            </div>

                            <!-- Message -->
                            <div>
                                <label for="cMessage" class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">
                                    Message <span class="text-red-400">*</span>
                                </label>
                                <textarea id="cMessage" required rows="4" placeholder="Tell us how we can help…"
                                          class="form-input w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 placeholder-slate-400 resize-none"></textarea>
                            </div>

                            <!-- Submit -->
                            <button type="submit"
                                    class="group w-full sm:w-auto px-8 py-3.5 bg-gradient-to-r from-navy to-accentBlue text-white text-sm font-semibold rounded-xl shadow-lg shadow-accentBlue/30 hover:shadow-xl hover:shadow-accentBlue/40 transition-all duration-300 flex items-center justify-center gap-2">
                                <span>Send Message</span>
                                <i class="fas fa-paper-plane text-xs transition-transform group-hover:translate-x-1"></i>
                            </button>

                            <!-- Success message -->
                            <div id="contactSuccess" class="hidden items-center gap-2 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3 rounded-xl">
                                <i class="fas fa-check-circle"></i>
                                <span>Thank you! Your message has been sent successfully.</span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- ===== INFO CARDS (40%) ===== -->
            <div class="lg:col-span-2 flex flex-col gap-4 md:gap-5">

                <!-- Help card -->
                <div class="bg-gradient-to-br from-navy to-navyLight rounded-3xl p-6 md:p-7 text-white shadow-xl shadow-navy/30 relative overflow-hidden group">
                    <div class="absolute -bottom-10 -right-10 w-36 h-36 bg-white/5 rounded-full group-hover:scale-110 transition-transform duration-500"></div>
                    <div class="relative">
                        <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center mb-4">
                            <i class="fas fa-headset text-accentOrange text-lg"></i>
                        </div>
                        <h3 class="font-serif text-xl md:text-2xl font-semibold mb-2">Need Help?</h3>
                        <p class="text-white/70 text-xs md:text-sm leading-relaxed mb-4">
                            Our support team is available 24/7. Click the chat button at the bottom-right to start a live conversation.
                        </p>
                        <button onclick="openChat()" class="inline-flex items-center gap-2 bg-accentOrange text-navy text-xs font-bold px-4 py-2.5 rounded-xl hover:bg-white transition-colors duration-300">
                            <i class="fas fa-comment-dots"></i> Start Live Chat
                        </button>
                    </div>
                </div>

                <!-- Contact details card -->
                <div class="bg-white rounded-3xl p-6 md:p-7 border border-slate-100 shadow-lg shadow-slate-200/50">
                    <h3 class="font-serif text-lg md:text-xl text-navy font-semibold mb-4">Reach us directly</h3>

                    <div class="space-y-4">
                        <!-- Email -->
                        <a href="mailto:support@haventra.com" class="flex items-start gap-3 group">
                            <div class="w-10 h-10 rounded-xl bg-accentBlue/10 flex items-center justify-center flex-shrink-0 group-hover:bg-accentBlue transition-colors duration-300">
                                <i class="fas fa-envelope text-accentBlue text-sm group-hover:text-white transition-colors"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[10px] uppercase tracking-wide text-slate-400 font-semibold">Email</p>
                                <p class="text-sm text-slate-700 font-medium truncate">support@haventra.com</p>
                            </div>
                        </a>

                        <!-- Phone -->
                        <a href="tel:+254700000000" class="flex items-start gap-3 group">
                            <div class="w-10 h-10 rounded-xl bg-accentTeal/10 flex items-center justify-center flex-shrink-0 group-hover:bg-accentTeal transition-colors duration-300">
                                <i class="fas fa-phone-alt text-accentTeal text-sm group-hover:text-white transition-colors"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[10px] uppercase tracking-wide text-slate-400 font-semibold">Phone</p>
                                <p class="text-sm text-slate-700 font-medium">+254 700 000 000</p>
                            </div>
                        </a>

                        <!-- WhatsApp -->
                        <a href="https://wa.me/254700000000" target="_blank" rel="noopener" class="flex items-start gap-3 group">
                            <div class="w-10 h-10 rounded-xl bg-[#25D366]/10 flex items-center justify-center flex-shrink-0 group-hover:bg-[#25D366] transition-colors duration-300">
                                <i class="fab fa-whatsapp text-[#25D366] text-base group-hover:text-white transition-colors"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[10px] uppercase tracking-wide text-slate-400 font-semibold">WhatsApp</p>
                                <p class="text-sm text-slate-700 font-medium">Chat with us</p>
                            </div>
                        </a>

                        <!-- Address -->
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-xl bg-accentOrange/10 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-map-marker-alt text-accentOrange text-sm"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[10px] uppercase tracking-wide text-slate-400 font-semibold">Office</p>
                                <p class="text-sm text-slate-700 font-medium leading-snug">Westlands, Nairobi, Kenya</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick links -->
                <div class="bg-white rounded-3xl p-5 md:p-6 border border-slate-100 shadow-lg shadow-slate-200/50">
                    <h3 class="font-serif text-base md:text-lg text-navy font-semibold mb-3">Quick Links</h3>
                    <div class="grid grid-cols-2 gap-2">
                        <a href="#" class="text-xs text-slate-500 hover:text-accentBlue transition-colors flex items-center gap-1.5">
                            <i class="fas fa-question-circle text-[10px]"></i> FAQs
                        </a>
                        <a href="#" class="text-xs text-slate-500 hover:text-accentBlue transition-colors flex items-center gap-1.5">
                            <i class="fas fa-file-alt text-[10px]"></i> Terms
                        </a>
                        <a href="#" class="text-xs text-slate-500 hover:text-accentBlue transition-colors flex items-center gap-1.5">
                            <i class="fas fa-shield-alt text-[10px]"></i> Privacy
                        </a>
                        <a href="#" class="text-xs text-slate-500 hover:text-accentBlue transition-colors flex items-center gap-1.5">
                            <i class="fas fa-book text-[10px]"></i> Guides
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>


<!-- ================= FLOATING BUTTONS ================= -->
<div class="fixed bottom-5 right-5 md:bottom-7 md:right-7 z-[5000] flex flex-col items-end gap-3">

    <!-- WhatsApp -->
    <a href="https://wa.me/254700000000" target="_blank" rel="noopener"
       class="floating-btn whatsapp"
       aria-label="Chat on WhatsApp">
        <i class="fab fa-whatsapp"></i>
        <span class="absolute bottom-full right-0 mb-2 px-2.5 py-1 bg-slate-800 text-white text-[10px] font-medium rounded-lg whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">Chat on WhatsApp</span>
    </a>

    <!-- Need Help / Live Chat -->
    <button onclick="toggleChat()"
            class="floating-btn help pulse-ring"
            aria-label="Need help? Open live chat">
        <i class="fas fa-comment-dots" id="helpIcon"></i>
        <span class="notif-dot" id="helpNotif"></span>
    </button>
</div>


<!-- ================= LIVE CHAT PANEL ================= -->
<div id="chatPanel" class="chat-panel bg-white">

    <!-- Header -->
    <div class="bg-gradient-to-r from-navy to-accentBlue px-4 py-3.5 flex items-center gap-3 flex-shrink-0">
        <div class="chat-avatar">
            <i class="fas fa-headset"></i>
        </div>
        <div class="flex-1 min-w-0">
            <h3 class="text-white text-sm font-semibold leading-tight">HAVENTRA Support</h3>
            <p class="text-white/70 text-[10px] flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full inline-block animate-pulse"></span>
                Online · Typically replies instantly
            </p>
        </div>
        <button onclick="closeChat()" class="w-8 h-8 flex items-center justify-center rounded-full text-white/80 hover:bg-white/20 transition-colors" aria-label="Close chat">
            <i class="fas fa-times text-sm"></i>
        </button>
    </div>

    <!-- Messages -->
    <div id="chatMessages" class="chat-messages">
        <!-- Messages injected here -->
    </div>

    <!-- Quick replies -->
    <div id="quickReplies" class="px-3 pt-2 pb-1 flex flex-wrap gap-1.5 flex-shrink-0 bg-white border-t border-slate-100">
        <button onclick="sendQuickReply('I need help with a property listing')" class="text-[10px] px-2.5 py-1.5 bg-slate-100 hover:bg-accentBlue hover:text-white text-slate-600 rounded-full transition-colors">Property listing</button>
        <button onclick="sendQuickReply('How do I book a short stay?')" class="text-[10px] px-2.5 py-1.5 bg-slate-100 hover:bg-accentBlue hover:text-white text-slate-600 rounded-full transition-colors">Booking help</button>
        <button onclick="sendQuickReply('I want to speak to an agent')" class="text-[10px] px-2.5 py-1.5 bg-slate-100 hover:bg-accentBlue hover:text-white text-slate-600 rounded-full transition-colors">Speak to agent</button>
    </div>

    <!-- Input -->
    <div class="px-3 py-3 bg-white border-t border-slate-100 flex-shrink-0">
        <form onsubmit="sendMessage(event)" class="flex items-center gap-2">
            <input type="text" id="chatInput" placeholder="Type your message…" autocomplete="off"
                   class="flex-1 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-full text-sm text-slate-700 placeholder-slate-400 form-input">
            <button type="submit"
                    class="w-10 h-10 flex items-center justify-center bg-gradient-to-r from-navy to-accentBlue text-white rounded-full hover:shadow-lg hover:shadow-accentBlue/30 transition-all duration-300 flex-shrink-0"
                    aria-label="Send message">
                <i class="fas fa-paper-plane text-xs"></i>
            </button>
        </form>
    </div>
</div>

<script>
/* ============================================================
   CONTACT FORM
   ============================================================ */
async function handleContactSubmit(e) {
    e.preventDefault();
    const btn = e.target.querySelector('button[type="submit"]');
    const original = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Sending…</span>';
    btn.disabled = true;

    const formData = {
        name: document.getElementById('cName').value.trim(),
        email: document.getElementById('cEmail').value.trim(),
        phone: '',
        subject: document.getElementById('cSubject').value || 'General Inquiry',
        message: document.getElementById('cMessage').value.trim(),
    };

    try {
        const res = await fetch('/api/contact.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(formData),
        });
        const data = await res.json();

        if (data.success) {
            btn.innerHTML = '<i class="fas fa-check"></i><span>Sent!</span>';
            btn.classList.replace('from-navy', 'from-emerald-600');
            btn.classList.replace('to-accentBlue', 'to-emerald-600');
            e.target.reset();
            const success = document.getElementById('contactSuccess');
            success.classList.remove('hidden');
            success.classList.add('flex');
            setTimeout(() => {
                success.classList.add('hidden');
                success.classList.remove('flex');
                btn.innerHTML = original;
                btn.classList.replace('from-emerald-600', 'from-navy');
                btn.classList.replace('to-emerald-600', 'to-accentBlue');
                btn.disabled = false;
            }, 5000);
        } else {
            btn.innerHTML = original;
            btn.disabled = false;
            alert(data.error || 'Failed to send message. Please try again.');
        }
    } catch (err) {
        btn.innerHTML = original;
        btn.disabled = false;
        alert('Network error. Please try again.');
    }
}


/* ============================================================
   LIVE CHAT (Database-backed)
   ============================================================ */
let chatOpen = false;
let hasOpenedChat = false;
let chatPollInterval = null;

const chatPanel = document.getElementById('chatPanel');
const chatMessages = document.getElementById('chatMessages');
const chatInput = document.getElementById('chatInput');
const helpIcon = document.getElementById('helpIcon');
const helpNotif = document.getElementById('helpNotif');

/* ---------- Open / Close ---------- */
function openChat() {
    chatOpen = true;
    chatPanel.classList.add('open');
    helpIcon.className = 'fas fa-times';
    if (helpNotif) helpNotif.style.display = 'none';

    if (!hasOpenedChat) {
        hasOpenedChat = true;
        loadChatMessages();
        startChatPolling();
    }

    setTimeout(() => chatInput.focus(), 500);
}

function closeChat() {
    chatOpen = false;
    chatPanel.classList.remove('open');
    helpIcon.className = 'fas fa-comment-dots';
    stopChatPolling();
}

function toggleChat() {
    if (chatOpen) closeChat();
    else openChat();
}

/* ---------- Polling for new messages ---------- */
function startChatPolling() {
    stopChatPolling();
    chatPollInterval = setInterval(loadChatMessages, 3000);
}

function stopChatPolling() {
    if (chatPollInterval) {
        clearInterval(chatPollInterval);
        chatPollInterval = null;
    }
}

/* ---------- Load messages from DB ---------- */
async function loadChatMessages() {
    try {
        const res = await fetch('/api/chat.php?limit=100');
        const data = await res.json();
        if (!data.success) return;

        const messages = data.messages || [];
        if (messages.length === 0) return;

        // Only render if panel is empty or we need to refresh
        const existingCount = chatMessages.querySelectorAll('.msg-row').length;
        if (existingCount === 0) {
            chatMessages.innerHTML = '';
            messages.forEach(msg => {
                const isSender = !msg.is_admin && msg.sender_name === 'Visitor';
                if (msg.is_admin) {
                    addReceiverMessageDOM(msg.message, msg.created_at);
                } else {
                    addSenderMessageDOM(msg.message, msg.created_at);
                }
            });
            scrollToBottom();
        }
    } catch (err) {
        // Silent fail for polling
    }
}

/* ---------- Add messages to DOM ---------- */
function addSenderMessage(text) {
    const time = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    const row = document.createElement('div');
    row.className = 'msg-row sender';
    row.innerHTML = `
        <div class="msg-bubble">
            ${escapeHtml(text)}
            <span class="msg-time">${time}</span>
        </div>
    `;
    chatMessages.appendChild(row);
    scrollToBottom();
}

function addReceiverMessage(text) {
    const time = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    const row = document.createElement('div');
    row.className = 'msg-row receiver';
    row.innerHTML = `
        <div class="msg-bubble">
            ${escapeHtml(text)}
            <span class="msg-time">${time}</span>
        </div>
    `;
    chatMessages.appendChild(row);
    scrollToBottom();
}

function addSenderMessageDOM(text, createdAt) {
    const time = createdAt ? new Date(createdAt).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) : new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    const row = document.createElement('div');
    row.className = 'msg-row sender';
    row.innerHTML = `
        <div class="msg-bubble">
            ${escapeHtml(text)}
            <span class="msg-time">${time}</span>
        </div>
    `;
    chatMessages.appendChild(row);
}

function addReceiverMessageDOM(text, createdAt) {
    const time = createdAt ? new Date(createdAt).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) : new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    const row = document.createElement('div');
    row.className = 'msg-row receiver';
    row.innerHTML = `
        <div class="msg-bubble">
            ${escapeHtml(text)}
            <span class="msg-time">${time}</span>
        </div>
    `;
    chatMessages.appendChild(row);
}

function showTyping() {
    const row = document.createElement('div');
    row.className = 'msg-row receiver';
    row.id = 'typingRow';
    row.innerHTML = `
        <div class="typing-indicator">
            <span></span><span></span><span></span>
        </div>
    `;
    chatMessages.appendChild(row);
    scrollToBottom();
}

function hideTyping() {
    const row = document.getElementById('typingRow');
    if (row) row.remove();
}

function scrollToBottom() {
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

/* ---------- Send ---------- */
async function sendMessage(e) {
    if (e) e.preventDefault();
    const text = chatInput.value.trim();
    if (!text) return;

    addSenderMessage(text);
    chatInput.value = '';
    chatInput.focus();

    try {
        const res = await fetch('/api/chat.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                sender_name: 'Visitor',
                sender_email: '',
                message: text,
                is_admin: 0,
            }),
        });
        const data = await res.json();
        if (!data.success) {
            // Message still shown locally; just log
            console.error('Chat send failed:', data.error);
        }
    } catch (err) {
        console.error('Chat send error:', err);
    }

    // Simulate admin typing + reply after a short delay
    setTimeout(() => {
        showTyping();
        setTimeout(() => {
            hideTyping();
            addReceiverMessage("Thanks for your message! Our support team will get back to you shortly.");
        }, 1500 + Math.random() * 1000);
    }, 600);
}

function sendQuickReply(text) {
    if (!chatOpen) openChat();
    chatInput.value = text;
    setTimeout(() => sendMessage(), 300);
}

/* ---------- Helpers ---------- */
function escapeHtml(str) {
    const div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
}

// Close on Escape
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && chatOpen) closeChat();
});

// Auto-open hint after a while (optional, small nudge)
setTimeout(() => {
    if (!hasOpenedChat && helpNotif) {
        helpNotif.style.display = 'block';
    }
}, 6000);
</script>
