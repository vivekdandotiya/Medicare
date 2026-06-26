<!-- Floating Chatbot Widget -->
<div x-data="{ open: false, messages: [], inputText: '', typing: false }" class="fixed bottom-6 right-6 z-[9999] font-sans">
    
    <!-- Floating Toggle Button -->
    <button @click="open = !open" 
            class="w-14 h-14 bg-gradient-to-tr from-teal-500 to-emerald-600 hover:from-teal-650 hover:to-emerald-700 text-white rounded-full flex items-center justify-center shadow-xl shadow-teal-500/30 hover:scale-105 active:scale-95 transition-all duration-300 border border-teal-400/20">
        <!-- Chat Icon -->
        <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
        </svg>
        <!-- Close Icon -->
        <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M6 18L18 6M6 6l12 12" />
        </svg>
        
        <!-- Pulsing Indicator for Customer Attention -->
        <span class="absolute top-0 right-0 flex h-3.5 w-3.5">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-orange-500 border-2 border-white"></span>
        </span>
    </button>

    <!-- Chat Panel Window -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="opacity-0 translate-y-8 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-8 scale-95"
         class="absolute bottom-18 right-0 w-[350px] sm:w-[380px] h-[500px] bg-white/95 backdrop-blur-xl border border-slate-200/60 rounded-3xl shadow-2xl flex flex-col overflow-hidden"
         style="display: none;">
         
        <!-- Header -->
        <div class="bg-gradient-to-r from-slate-900 to-teal-950 p-4 text-white flex justify-between items-center border-b border-teal-950/20">
            <div class="flex items-center gap-2.5">
                <span class="w-8.5 h-8.5 rounded-lg bg-teal-500 flex items-center justify-center text-white font-extrabold text-sm shadow-md">M</span>
                <div>
                    <h3 class="font-extrabold text-sm tracking-wide">Medicare AI Advisor</h3>
                    <div class="flex items-center gap-1.5 mt-0.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-450 animate-pulse"></span>
                        <span class="text-[9px] text-teal-300 font-bold uppercase tracking-wider">Clinical Bot Online</span>
                    </div>
                </div>
            </div>
            <button @click="open = false" class="text-slate-400 hover:text-white transition p-1 hover:bg-white/10 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Chat messages container -->
        <div id="chatbot-messages-box" class="flex-1 p-4 overflow-y-auto space-y-3.5 scroll-smooth bg-slate-50/40">
            
            <!-- Bot Greeting message -->
            <div class="flex items-start gap-2.5 max-w-[85%]">
                <div class="w-7 h-7 rounded-lg bg-teal-600 text-white flex items-center justify-center text-xs font-bold shrink-0 shadow-sm">AI</div>
                <div class="bg-white border border-slate-200/50 rounded-2xl rounded-tl-none p-3 shadow-sm text-xs text-slate-700 leading-relaxed font-medium">
                    Hello! I'm your Medicare Assistant. I can recommend suitable remedies for common symptoms (like fever, cough, cold, pain) or general wellness.
                </div>
            </div>

            <!-- Dynamic Messages -->
            <template x-for="(msg, index) in messages" :key="index">
                <div :class="msg.isUser ? 'justify-end' : 'justify-start'" class="flex items-start gap-2.5 w-full">
                    <div x-show="!msg.isUser" class="w-7 h-7 rounded-lg bg-teal-600 text-white flex items-center justify-center text-xs font-bold shrink-0 shadow-sm">AI</div>
                    <div :class="msg.isUser ? 'bg-gradient-to-r from-teal-600 to-teal-700 text-white rounded-tr-none' : 'bg-white border border-slate-200/50 text-slate-700 rounded-tl-none'"
                         class="rounded-2xl p-3 shadow-sm text-xs leading-relaxed font-medium max-w-[80%]"
                         x-html="msg.text">
                    </div>
                </div>
            </template>

            <!-- Typing indicator -->
            <div x-show="typing" class="flex items-start gap-2.5 max-w-[85%]" style="display: none;">
                <div class="w-7 h-7 rounded-lg bg-teal-600 text-white flex items-center justify-center text-xs font-bold shrink-0">AI</div>
                <div class="bg-white border border-slate-200/50 rounded-2xl rounded-tl-none px-4 py-3 shadow-sm text-xs flex items-center gap-1">
                    <span class="w-1.5 h-1.5 bg-slate-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></span>
                    <span class="w-1.5 h-1.5 bg-slate-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></span>
                    <span class="w-1.5 h-1.5 bg-slate-400 rounded-full animate-bounce" style="animation-delay: 0.3s"></span>
                </div>
            </div>

            <!-- Quick Suggestion Chips -->
            <div class="pt-2 flex flex-wrap gap-2">
                <button @click="sendSuggestion('I have a fever')" class="bg-teal-50 hover:bg-teal-100 border border-teal-500/10 hover:border-teal-500/20 text-teal-700 text-[10px] font-bold px-3 py-1.5 rounded-full transition shadow-sm">🤒 Fever</button>
                <button @click="sendSuggestion('I have a cough')" class="bg-teal-50 hover:bg-teal-100 border border-teal-500/10 hover:border-teal-500/20 text-teal-700 text-[10px] font-bold px-3 py-1.5 rounded-full transition shadow-sm">😷 Cough/Cold</button>
                <button @click="sendSuggestion('I have a headache')" class="bg-teal-50 hover:bg-teal-100 border border-teal-500/10 hover:border-teal-500/20 text-teal-700 text-[10px] font-bold px-3 py-1.5 rounded-full transition shadow-sm">🤕 Headache</button>
                <button @click="sendSuggestion('I need multivitamins')" class="bg-teal-50 hover:bg-teal-100 border border-teal-500/10 hover:border-teal-500/20 text-teal-700 text-[10px] font-bold px-3 py-1.5 rounded-full transition shadow-sm">💊 Multivitamins</button>
            </div>
            
        </div>

        <!-- Input Panel Form -->
        <div class="p-3 bg-white border-t border-slate-100 flex gap-2 items-center">
            <input type="text" 
                   x-model="inputText" 
                   @keydown.enter="submitMessage()" 
                   placeholder="Ask for tablet advice (e.g. fever)..."
                   class="flex-1 bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:border-teal-500 focus:ring-0 focus:outline-none font-medium placeholder:text-slate-400">
            <button @click="submitMessage()" 
                    class="w-8.5 h-8.5 bg-teal-600 hover:bg-teal-750 text-white rounded-xl flex items-center justify-center transition shadow-md shadow-teal-500/10 active:scale-90">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9-7-9-7v14z" />
                </svg>
            </button>
        </div>

    </div>

    <!-- Script engine -->
    <script>
        function sendSuggestion(suggestionText) {
            const el = document.querySelector('[x-data]');
            if (el && el.__x) {
                el.__x.$data.inputText = suggestionText;
                el.__x.$data.submitMessage();
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const el = document.querySelector('[x-data]');
            if (!el) return;

            el.__x.$data.submitMessage = function() {
                const text = this.inputText.trim();
                if (!text) return;

                // Push user message
                this.messages.push({ text: text, isUser: true });
                this.inputText = '';
                this.typing = true;

                // Scroll messages container to bottom
                setTimeout(() => {
                    const box = document.getElementById('chatbot-messages-box');
                    if (box) box.scrollTop = box.scrollHeight;
                }, 50);

                // Process bot response
                setTimeout(() => {
                    let response = '';
                    const lowerText = text.toLowerCase();

                    if (lowerText.includes('fever') || lowerText.includes('temp') || lowerText.includes('warm')) {
                        response = `For fever and associated pain, we suggest standard OTC medicines:<br><br>
                        • <a href="/medicines?search=Dolo+650" class="text-teal-650 font-bold hover:underline">Dolo 650 Tablet</a> (₹26.50) – Paracetamol for fast relief.<br>
                        • <a href="/medicines?search=Combiflam" class="text-teal-650 font-bold hover:underline">Combiflam Tablet</a> (₹42.00) – Relieves fever and muscle pain.<br><br>
                        <span class="text-[10px] text-slate-450 italic">Always check the prescription requirements or consult a clinic if symptoms persist.</span>`;
                    } 
                    else if (lowerText.includes('cough') || lowerText.includes('throat') || lowerText.includes('cold') || lowerText.includes('cough syrup')) {
                        response = `For cough and throat congestion, we recommend:<br><br>
                        • <a href="/medicines?search=Benadryl" class="text-teal-650 font-bold hover:underline">Benadryl Cough Syrup</a> (₹119.00) – For dry cough.<br>
                        • <a href="/medicines?search=Honitus" class="text-teal-650 font-bold hover:underline">Dabur Honitus Herbal Syrup</a> (₹89.00) – Ayurvedic cough remedy.<br>
                        • <a href="/medicines?search=Koflet" class="text-teal-650 font-bold hover:underline">Himalaya Koflet Syrup</a> (₹99.00) – Soothing herbal relief.<br>
                        • <a href="/medicines?search=Strepsils" class="text-teal-650 font-bold hover:underline">Strepsils Throat Lozenges</a> (₹30.00) – Rapid throat relief.<br><br>
                        <span class="text-[10px] text-slate-455 italic">For productive cough with mucus, you may need an Rx syrup like <a href="/medicines?search=Ascoril" class="text-teal-600 underline">Ascoril LS</a>.</span>`;
                    }
                    else if (lowerText.includes('headache') || lowerText.includes('pain') || lowerText.includes('body') || lowerText.includes('migraine')) {
                        response = `For headache, muscle, or body ache, we suggest:<br><br>
                        • <a href="/medicines?search=Crocin" class="text-teal-650 font-bold hover:underline">Crocin Pain Relief</a> (₹39.00) – Contains caffeine and paracetamol.<br>
                        • <a href="/medicines?search=Zandu" class="text-teal-650 font-bold hover:underline">Zandu Balm Ultra Power</a> (₹38.00) – Ayurvedic pain ointment.<br>
                        • <a href="/medicines?search=Vicks" class="text-teal-650 font-bold hover:underline">Vicks Vaporub</a> (₹135.00) – Rub for body ache & cold.<br><br>
                        • <a href="/medicines?search=Combiflam" class="text-teal-650 font-bold hover:underline">Combiflam Tablet</a> (₹42.00) – Double action ibuprofen + paracetamol.`;
                    }
                    else if (lowerText.includes('vitamin') || lowerText.includes('multivitamin') || lowerText.includes('immunity') || lowerText.includes('calcium') || lowerText.includes('nutrition') || lowerText.includes('energy')) {
                        response = `For strength, nutrition, and immunity boosts:<br><br>
                        • <a href="/medicines?search=Becosules" class="text-teal-650 font-bold hover:underline">Becosules Z Capsules</a> (₹48.00) – Vitamin B-Complex & Zinc.<br>
                        • <a href="/medicines?search=Limcee" class="text-teal-650 font-bold hover:underline">Limcee Vitamin C Tablet</a> (₹20.00) – Chewable immunity supplement.<br>
                        • <a href="/medicines?search=Shelcal" class="text-teal-650 font-bold hover:underline">Shelcal 500 Calcium</a> (₹99.00) – Strong bones and joints.<br>
                        • <a href="/medicines?search=Chyawanprash" class="text-teal-650 font-bold hover:underline">Dabur Chyawanprash</a> (₹195.00) – Premium ayurvedic shield.<br><br>
                        All available OTC with no prescription requirements!`;
                    }
                    else if (lowerText.includes('acidity') || lowerText.includes('gas') || lowerText.includes('indigestion') || lowerText.includes('stomach')) {
                        response = `For stomach gas, acidity, or indigestion, we recommend:<br><br>
                        • <a href="/medicines?search=Gelusil" class="text-teal-650 font-bold hover:underline">Gelusil Liquid Antacid</a> (₹139.00) – Fast relief from acidity & gas.<br>
                        • <a href="/medicines?search=Hara" class="text-teal-650 font-bold hover:underline">Dabur Pudin Hara Active</a> (₹52.00) – Mint-based stomach ache relief.`;
                    }
                    else if (lowerText.includes('prescription') || lowerText.includes('rx') || lowerText.includes('upload') || lowerText.includes('hub')) {
                        response = `For prescription items (marked with an <span class="bg-red-50 text-red-650 text-[10px] font-bold px-1 py-0.5 rounded border border-red-200">Rx Required</span> badge):<br><br>
                        1. Visit the <a href="/prescriptions" class="text-teal-650 font-bold underline">Prescription Hub</a> page.<br>
                        2. Fill in the patient details and upload a photo/scan of your doctor's slip.<br>
                        3. Once verified by our pharmacists, the status updates to approved, allowing checkout.<br><br>
                        Let us know if you need assistance during the process!`;
                    }
                    else {
                        response = `I appreciate your query. For common conditions like fever, cold, throat ache, or pain, we offer multiple OTC remedies.<br><br>
                        Try searching for items like **Dolo 650**, **Strepsils**, **Limcee**, or **Benadryl**.<br><br>
                        If you have severe or chronic symptoms, please consult a registered medical professional.`;
                    }

                    // Append Medical Disclaimer
                    response += `<div class="mt-3.5 border-t border-slate-100 pt-2 text-[9px] text-slate-400 leading-normal font-semibold">
                    ⚠️ Disclaimer: Advice is informational. Always seek medical professional guidance for treatment courses.
                    </div>`;

                    this.typing = false;
                    this.messages.push({ text: response, isUser: false });

                    // Scroll messages container to bottom
                    setTimeout(() => {
                        const box = document.getElementById('chatbot-messages-box');
                        if (box) box.scrollTop = box.scrollHeight;
                    }, 50);

                }, 900);
            };
        });
    </script>
</div>
