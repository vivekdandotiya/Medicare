<div x-data="{
    open: false,
    medicine: {},
    activeTab: 'dosage',
    ratingCount: 48,
    reviews: [
        { name: 'Rohan Sharma', rating: 5, comment: 'Highly effective! Within 2 days of regular dosage, the symptoms completely subsided.', date: '2 days ago' },
        { name: 'Dr. Vivek D.', rating: 5, comment: 'Standard clinical composition, highly recommended alternative for this therapy.', date: '5 days ago' },
        { name: 'Anjali Gupta', rating: 4, comment: 'Good authentic packaging. The product arrived within 24 hours. Excellent service.', date: '1 week ago' }
    ],
    get dosageText() {
        if (this.medicine.category_name === 'Syrups') {
            return 'Adults: 10ml (2 teaspoonfuls) 3-4 times a day.\nChildren: 5ml (1 teaspoonful) 3 times a day or as recommended by the physician.\nShake well before use.';
        }
        return 'Take 1 tablet daily with a glass of water after food, or as directed by your physician. Do not crush or chew the tablet.';
    },
    get warningsText() {
        if (this.medicine.category_name === 'Syrups') {
            return 'Store in a cool dry place, away from direct sunlight. Do not exceed the recommended dose. Keep out of reach of infants.';
        }
        if (this.medicine.prescription_required) {
            return 'Warning: Prescription required medication. Do not consume without medical supervision. May cause dizziness or drowsiness in sensitive individuals.';
        }
        return 'Not for children under 12 years unless advised by a doctor. If pregnant or breastfeeding, consult your healthcare provider before use.';
    }
}"
@open-medicine-modal.window="medicine = $event.detail; open = true; activeTab = 'dosage';"
class="fixed inset-0 z-50 flex justify-end font-sans"
x-show="open"
style="display: none;">
    
    <!-- Backdrop overlay -->
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" 
         @click="open = false" 
         x-show="open" 
         x-transition.opacity></div>

    <!-- Sliding Panel Drawer -->
    <div class="bg-white w-full max-w-lg h-full shadow-2xl relative z-10 flex flex-col justify-between border-l border-slate-200 overflow-hidden"
         x-show="open"
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full">
         
        <!-- Header -->
        <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-teal-500 animate-pulse"></span>
                <span class="text-xs font-black text-slate-400 uppercase tracking-widest">Clinical Product File</span>
            </div>
            <button @click="open = false" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition active:scale-95">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Scrollable content -->
        <div class="flex-1 overflow-y-auto p-6 space-y-6">
            <!-- Product Identity Header -->
            <div class="flex gap-4">
                <div class="w-24 h-24 bg-slate-50 border border-slate-150 rounded-2xl flex items-center justify-center p-2 overflow-hidden flex-shrink-0">
                    <template x-if="medicine.image">
                        <img :src="'/' + medicine.image" class="object-contain h-full w-full">
                    </template>
                    <template x-if="!medicine.image">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-teal-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 9.172V5L8 4z" />
                        </svg>
                    </template>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span x-text="medicine.brand_name" class="text-[10px] text-slate-400 font-extrabold uppercase tracking-widest"></span>
                        <template x-if="medicine.prescription_required">
                            <span class="bg-red-500/10 border border-red-500/20 text-red-655 text-[8px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">Rx Required</span>
                        </template>
                    </div>
                    <h3 x-text="medicine.name" class="text-xl font-black text-slate-800 tracking-tight leading-snug mt-1"></h3>
                    <span x-text="medicine.category_name" class="inline-block mt-2 bg-teal-50 text-teal-700 text-[10px] font-bold px-2.5 py-0.5 rounded-md border border-teal-200/20"></span>
                    <p class="text-xs text-slate-500 mt-2 font-medium" x-text="medicine.description"></p>
                </div>
            </div>

            <!-- Pricing Bar -->
            <div class="bg-slate-50/65 border border-slate-200/60 rounded-2xl p-4 flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Selling Price</p>
                    <div class="flex items-baseline gap-2 mt-1">
                        <span class="text-2xl font-black text-teal-700">₹<span x-text="Number(medicine.selling_price).toFixed(2)"></span></span>
                        <template x-if="Number(medicine.mrp) > Number(medicine.selling_price)">
                            <span class="text-xs text-slate-400 line-through">₹<span x-text="Number(medicine.mrp).toFixed(2)"></span></span>
                        </template>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Stock Status</p>
                    <template x-if="medicine.stock_quantity > 0">
                        <span class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-600 mt-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            In Stock (<span x-text="medicine.stock_quantity"></span> units)
                        </span>
                    </template>
                    <template x-if="medicine.stock_quantity <= 0">
                        <span class="inline-flex items-center gap-1.5 text-xs font-bold text-red-500 mt-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-ping"></span>
                            Out of Stock
                        </span>
                    </template>
                </div>
            </div>

            <!-- Interactive Tabbed Details -->
            <div class="border border-slate-200/60 rounded-2xl overflow-hidden bg-white">
                <!-- Tabs list -->
                <div class="flex border-b border-slate-100 text-xs font-bold bg-slate-50/30">
                    <button @click="activeTab = 'dosage'" type="button"
                            :class="activeTab === 'dosage' ? 'bg-white border-b-2 border-teal-500 text-teal-700' : 'text-slate-500 hover:text-slate-700'"
                            class="flex-1 py-3 text-center transition">
                        Usage & Dosage
                    </button>
                    <button @click="activeTab = 'safety'" type="button"
                            :class="activeTab === 'safety' ? 'bg-white border-b-2 border-teal-500 text-teal-700' : 'text-slate-500 hover:text-slate-700'"
                            class="flex-1 py-3 text-center transition">
                        Safety & Warnings
                    </button>
                    <button @click="activeTab = 'clinic'" type="button"
                            :class="activeTab === 'clinic' ? 'bg-white border-b-2 border-teal-500 text-teal-700' : 'text-slate-500 hover:text-slate-700'"
                            class="flex-1 py-3 text-center transition">
                        Medicare Certified
                    </button>
                </div>

                <!-- Tabs content -->
                <div class="p-5 min-h-[120px]">
                    <div x-show="activeTab === 'dosage'" x-transition class="text-xs text-slate-650 leading-relaxed whitespace-pre-line font-medium" x-text="dosageText"></div>
                    <div x-show="activeTab === 'safety'" x-transition class="text-xs text-slate-650 leading-relaxed whitespace-pre-line font-medium" x-text="warningsText"></div>
                    <div x-show="activeTab === 'clinic'" x-transition class="space-y-3">
                        <div class="flex items-start gap-2.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5 text-teal-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.952 11.952 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            <div>
                                <p class="text-xs font-bold text-slate-800">100% Quality Checked</p>
                                <p class="text-[10px] text-slate-500 leading-relaxed mt-0.5 font-medium">This pharmaceutical item has been certified batch-clean and verified by registered Medicare pharmacologists.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-2.5 pt-2 border-t border-slate-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5 text-orange-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <div>
                                <p class="text-xs font-bold text-slate-800">Fresh Stock Guarantee</p>
                                <p class="text-[10px] text-slate-500 leading-relaxed mt-0.5 font-medium">Expires no earlier than December 2027. Sourced directly from approved manufacturer facilities.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alternative Brand Substitutes Shelf -->
            <div>
                <h4 class="text-[10px] font-extrabold text-slate-450 uppercase tracking-widest mb-3">Equivalent Substitutes</h4>
                <div class="bg-slate-50/50 rounded-2xl p-4 border border-slate-150 space-y-2">
                    <p class="text-[10px] text-slate-450 font-semibold mb-2">Need a faster alternative or different manufacturer?</p>
                    <div class="flex flex-wrap gap-2">
                        <template x-if="medicine.category_name === 'Syrups'">
                            <div class="flex flex-wrap gap-2">
                                <a href="/medicines?search=Honitus" class="bg-white hover:bg-teal-50 border border-slate-200 text-[10px] font-bold text-slate-700 px-3 py-1.5 rounded-lg transition active:scale-95">Dabur Honitus</a>
                                <a href="/medicines?search=Koflet" class="bg-white hover:bg-teal-50 border border-slate-200 text-[10px] font-bold text-slate-700 px-3 py-1.5 rounded-lg transition active:scale-95">Himalaya Koflet</a>
                            </div>
                        </template>
                        <template x-if="medicine.category_name !== 'Syrups'">
                            <div class="flex flex-wrap gap-2">
                                <a href="/medicines?search=Limcee" class="bg-white hover:bg-teal-50 border border-slate-200 text-[10px] font-bold text-slate-700 px-3 py-1.5 rounded-lg transition active:scale-95">Limcee Vitamin C</a>
                                <a href="/medicines?category=1" class="bg-white hover:bg-teal-50 border border-slate-200 text-[10px] font-bold text-slate-700 px-3 py-1.5 rounded-lg transition active:scale-95">Generic Alternatives</a>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Ratings Breakdown & Customer Feedback Widget -->
            <div>
                <h4 class="text-[10px] font-extrabold text-slate-450 uppercase tracking-widest mb-3">Customer Ratings</h4>
                <div class="border border-slate-200/60 rounded-2xl p-4 bg-white space-y-4 shadow-sm">
                    <!-- Average stars header -->
                    <div class="flex items-center gap-4">
                        <div class="text-center">
                            <p class="text-3xl font-black text-slate-800">4.8</p>
                            <p class="text-[10px] text-slate-400 font-bold uppercase mt-0.5">out of 5</p>
                        </div>
                        <div class="flex-1 space-y-1">
                            <!-- Star row 5 -->
                            <div class="flex items-center gap-2 text-[10px] font-bold text-slate-655">
                                <span class="w-3">5★</span>
                                <div class="flex-1 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-teal-500 rounded-full" style="width: 85%"></div>
                                </div>
                                <span class="w-6 text-right">85%</span>
                            </div>
                            <!-- Star row 4 -->
                            <div class="flex items-center gap-2 text-[10px] font-bold text-slate-655">
                                <span class="w-3">4★</span>
                                <div class="flex-1 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-teal-400 rounded-full" style="width: 10%"></div>
                                </div>
                                <span class="w-6 text-right">10%</span>
                            </div>
                            <!-- Star row 3 -->
                            <div class="flex items-center gap-2 text-[10px] font-bold text-slate-655">
                                <span class="w-3">3★</span>
                                <div class="flex-1 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-amber-400 rounded-full" style="width: 5%"></div>
                                </div>
                                <span class="w-6 text-right">5%</span>
                            </div>
                        </div>
                    </div>

                    <!-- Client comments list -->
                    <div class="border-t border-slate-100 pt-4 space-y-3.5">
                        <template x-for="rev in reviews">
                            <div class="text-xs">
                                <div class="flex items-center justify-between font-bold text-slate-700">
                                    <span x-text="rev.name"></span>
                                    <span class="text-teal-600" x-text="'★'.repeat(rev.rating) + '☆'.repeat(5 - rev.rating)"></span>
                                </div>
                                <p class="text-slate-500 font-medium mt-1 leading-relaxed" x-text="rev.comment"></p>
                                <p class="text-[10px] text-slate-400 font-medium text-right mt-0.5" x-text="rev.date"></p>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Drawer Footer -->
        <div class="p-6 border-t border-slate-100 bg-slate-50/50 flex gap-3">
            @auth
                @if(auth()->user()->hasRole('customer'))
                    <form action="" method="POST" :action="`/cart/add/${medicine.id}`" class="flex-1">
                        @csrf
                        <button type="submit" :disabled="medicine.stock_quantity <= 0" 
                                class="w-full bg-teal-650 hover:bg-teal-700 text-white font-bold text-xs py-3.5 rounded-xl transition shadow-md shadow-teal-500/10 active:scale-95 disabled:bg-slate-100 disabled:text-slate-400 disabled:cursor-not-allowed">
                            Add to Cart
                        </button>
                    </form>
                    
                    <form action="" method="POST" :action="`/cart/add/${medicine.id}`" class="flex-1">
                        @csrf
                        <input type="hidden" name="buy_now" value="1">
                        <button type="submit" :disabled="medicine.stock_quantity <= 0"
                                class="w-full bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-bold text-xs py-3.5 rounded-xl transition shadow-md shadow-orange-500/10 active:scale-95 disabled:bg-slate-100 disabled:text-slate-400 disabled:cursor-not-allowed">
                            Buy Now
                        </button>
                    </form>
                @else
                    <a :href="`/medicines/${medicine.id}/edit`" class="w-full bg-slate-150 hover:bg-slate-200 border border-slate-200 text-slate-700 font-bold text-xs py-3.5 text-center rounded-xl transition block">
                        Modify Product Listing
                    </a>
                @endif
            @else
                <a href="{{ route('login') }}" class="w-full bg-teal-650 hover:bg-teal-700 text-white font-bold text-xs py-3.5 text-center rounded-xl transition block shadow-md shadow-teal-500/10">
                    Log in to Purchase Item
                </a>
            @endauth
        </div>
    </div>
</div>
