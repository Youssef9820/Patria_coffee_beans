<x-app-layout>
    <!-- 1. ASSETS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- 2. STYLES -->
    <style>
        [x-cloak] { display: none !important; }
        
        /* Theme Colors */
        :root {
            --patria-cream: #FAF9F6;
            --patria-brown: #3E2723;
            --patria-green: #556B2F;
            --patria-green-dark: #3e4f21;
            --patria-brown-light: #5D4037;
        }

        body { font-family: 'Inter', sans-serif; background-color: var(--patria-cream); color: #333; }
        .brand-font { font-family: 'Playfair Display', serif; }

        /* Custom UI Elements */
        .btn-premium {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            border-radius: 8px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .btn-premium:hover { transform: translateY(-2px); box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); }
        .btn-premium:active { transform: translateY(0); }

        .btn-brown { background-color: var(--patria-brown); color: white; }
        .btn-green { background-color: var(--patria-green); color: white; }
        .btn-dark { background-color: #1F2937; color: white; }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            border-left: 6px solid var(--patria-brown);
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
            transition: transform 0.2s;
        }
        .stat-card:hover { transform: scale(1.01); }

        .table-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            border-top: 5px solid var(--patria-brown);
        }

        .custom-th {
            background-color: #F3F4F6;
            color: var(--patria-brown);
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            padding: 16px;
        }

        /* Price Input Layout */
        .price-group { display: flex; align-items: flex-end; gap: 8px; }
        .price-wrapper { flex: 1; }
        .equals-sign { padding-bottom: 12px; font-weight: bold; color: #9CA3AF; font-size: 1.2rem; }
        
        /* Modal Backdrop */
        .modal-backdrop { background-color: rgba(31, 41, 55, 0.6); backdrop-filter: blur(4px); }
    </style>

<!-- No x-slot header needed for new layout -->

    @php
        $isRtl = app()->getLocale() == 'ar';
        $dir = $isRtl ? 'rtl' : 'ltr';
        // Get Month Name for Display
        $monthName = \Carbon\Carbon::create()->month($month ?? date('m'))->locale(app()->getLocale())->monthName;
    @endphp

    <!-- MAIN APP CONTAINER (One x-data for everything) -->
    <div dir="{{ $dir }}" 
         x-data="{ showTypeModal: false, showBatchModal: false, showPayModal: false, showFinancialModal: false }" 
         @open-type-modal.window="showTypeModal = true" 
         @open-batch-modal.window="showBatchModal = true" 
         @open-pay-modal.window="showPayModal = true"
         @keydown.escape.window="showTypeModal = false; showBatchModal = false; showPayModal = false; showFinancialModal = false" 
         class="py-8">
         
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
<!-- 1. HEADER & FILTER BAR -->
            <div class="flex flex-col md:flex-row justify-between items-end gap-4 bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                
                <!-- Title & Current Period -->
                <div>
                    <h2 class="font-bold text-2xl text-[#3E2723] brand-font flex items-center gap-2">
                        <i class="fa-solid fa-leaf text-[#556B2F]"></i> {{ __('messages.green_coffee_inventory') }}
                    </h2>
                    <p class="text-sm text-gray-500 mt-1 font-bold">
                        {{ __('messages.period') }}: <span class="text-[#556B2F]">{{ $monthName }} {{ $year ?? date('Y') }}</span>
                    </p>
                </div>

<!-- Date Filter Form -->
                <form method="GET" action="{{ route('green-coffee.index') }}" class="flex items-center gap-2 bg-gray-50 p-2 rounded-lg border border-gray-200">
                    
                    <!-- Month Select -->
                    <select name="month" onchange="this.form.submit()" class="bg-transparent border-none text-sm font-bold focus:ring-0 text-gray-700 cursor-pointer hover:text-[#3E2723]">
                        @for($m=1; $m<=12; $m++)
                            <option value="{{ $m }}" {{ $m == ($month ?? date('m')) ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->locale('en')->monthName }}
                            </option>
                        @endfor
                    </select>
                    
                    <span class="text-gray-300">|</span>
                    
                    <!-- Year Select -->
                    <select name="year" onchange="this.form.submit()" class="bg-transparent border-none text-sm font-bold focus:ring-0 text-gray-700 cursor-pointer hover:text-[#3E2723]">
                        @for($y=date('Y'); $y>=2023; $y--)
                            <option value="{{ $y }}" {{ $y == ($year ?? date('Y')) ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>

                    <!-- Filter Button (Optional now, but good to have) -->
                    <button type="submit" class="bg-[#3E2723] text-white p-2 rounded-md hover:bg-[#2b1b18] transition shadow-sm">
                        <i class="fa-solid fa-filter"></i>
                    </button>
                </form>
            </div>

            <!-- 2. PERIOD STATS ROW (Beginning -> Added -> Ending) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Beginning Balance -->
                <div class="stat-card" style="border-left-color: #FFA000;">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('messages.beginning_balance') }}</p>
                    <div class="flex items-baseline gap-2 mt-1">
                        <p class="text-2xl font-bold text-[#FFA000] brand-font">{{ number_format($globalBeginningWeight ?? 0, 0) }}</p>
                        <span class="text-xs text-gray-400 font-bold">kg</span>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">{{ number_format($globalBeginningValue ?? 0, 0) }} EGP</p>
                </div>
                
                <!-- Added This Month -->
                <div class="stat-card" style="border-left-color: #556B2F;">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('messages.added_this_month') }}</p>
                    <div class="flex items-baseline gap-2 mt-1">
                        <p class="text-2xl font-bold text-[#556B2F] brand-font">+ {{ number_format(($globalEndingWeight ?? 0) - ($globalBeginningWeight ?? 0), 0) }}</p>
                        <span class="text-xs text-gray-400 font-bold">kg</span>
                    </div>
                </div>

                <!-- Ending Balance -->
                <div class="stat-card" style="border-left-color: #3E2723;">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('messages.ending_balance') }}</p>
                    <div class="flex items-baseline gap-2 mt-1">
                        <p class="text-3xl font-bold text-[#3E2723] brand-font">{{ number_format($globalEndingWeight ?? 0, 0) }}</p>
                        <span class="text-sm text-gray-400 font-bold">kg</span>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">{{ number_format($globalEndingValue ?? 0, 0) }} EGP</p>
                </div>
            </div>

            <!-- 3. ACTION BUTTONS -->
            <div class="flex flex-wrap gap-3 justify-end">
                <button onclick="openTypeModal('create')" class="btn-premium btn-brown py-2 px-4 text-sm"><i class="fa-solid fa-plus"></i> {{ __('messages.add_new_type') }}</button>
                <button onclick="openBatchModal('create')" class="btn-premium btn-green py-2 px-4 text-sm"><i class="fa-solid fa-box-open"></i> {{ __('messages.add_new_batch') }}</button>
                <button @click="showFinancialModal = true" class="btn-premium btn-dark py-2 px-4 text-sm"><i class="fa-solid fa-file-invoice-dollar"></i> {{ __('messages.financial_report') }}</button>
                <a href="{{ route('switchLang', $isRtl ? 'en' : 'ar') }}" class="px-4 py-2 rounded-lg border border-gray-300 bg-white text-gray-600 font-bold hover:bg-gray-50 transition text-sm flex items-center gap-2"><i class="fa-solid fa-globe"></i> {{ $isRtl ? 'EN' : 'AR' }}</a>
            </div>

<!-- 4. INVENTORY TABLE (PERIOD VIEW) -->
            <div class="table-container">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead>
                        <tr>
                            <th class="custom-th text-{{ $isRtl ? 'right' : 'left' }}">{{ __('messages.coffee_type') }}</th>
                            <th class="custom-th text-{{ $isRtl ? 'right' : 'left' }}">{{ __('messages.beginning_balance') }}</th>
                            <th class="custom-th text-{{ $isRtl ? 'right' : 'left' }}">{{ __('messages.added_this_month') }}</th>
                            <th class="custom-th text-{{ $isRtl ? 'right' : 'left' }}">{{ __('messages.ending_balance') }}</th>
                            <th class="custom-th text-center">{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @foreach($types as $type)
                            <tr x-data="{ expanded: false }" class="hover:bg-[#FAF9F6] transition group">
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="p-2 bg-[#F5F5F4] rounded text-[#3E2723]">
                                            <i class="fa-solid fa-mug-hot"></i>
                                        </div>
                                        <span class="text-lg font-bold text-gray-800 brand-font">{{ $type->name }}</span>
                                        
                                        <!-- Edit/Delete Type -->
                                        <div class="flex gap-1 ml-2 opacity-100">
                                            <button onclick="openTypeModal('edit', '{{ $type->id }}', '{{ $type->name }}')" class="p-1 text-blue-500 hover:text-blue-700 transition"><i class="fa-solid fa-pen"></i></button>
                                            <form action="{{ route('green-coffee.destroyType', $type->id) }}" method="POST" onsubmit="return confirm('Delete Type & All Inventory?');" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="p-1 text-red-400 hover:text-red-600 transition"><i class="fa-solid fa-trash"></i></button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                                
                                <!-- Beginning -->
                                <td class="px-6 py-5 text-gray-500 font-medium">
                                    {{ number_format($type->beginning_weight ?? 0, 2) }} kg
                                </td>
                                
                                <!-- Added -->
                                <td class="px-6 py-5 text-[#556B2F] font-bold">
                                    + {{ number_format($type->added_weight ?? 0, 2) }} kg
                                </td>
                                
                                <!-- Ending -->
                                <td class="px-6 py-5 font-bold text-[#3E2723] text-lg">
                                    {{ number_format($type->ending_weight ?? 0, 2) }} kg
                                </td>

                                <td class="px-6 py-5 text-center">
                                    <button @click="expanded = !expanded" class="text-[#3E2723] font-bold text-sm hover:underline focus:outline-none">
                                        <span x-show="!expanded"><i class="fa-solid fa-chevron-down"></i> {{ __('messages.details') }}</span>
                                        <span x-show="expanded"><i class="fa-solid fa-chevron-up"></i> {{ __('messages.close') }}</span>
                                    </button>
                                </td>
                            
                                <!-- NESTED DETAILS ROW -->
                                <template x-if="expanded">
                                    <tr class="bg-[#FAF9F6] border-y border-gray-200" x-transition.opacity.duration.300ms>
                                        <td colspan="5" class="p-4 md:p-6">
                                            <div class="bg-white rounded-lg border border-gray-200 shadow-inner p-4">
                                                <div class="flex justify-between items-center mb-4">
                                                    <h4 class="font-bold text-[#3E2723] uppercase text-xs tracking-widest"><i class="fa-solid fa-list-ul"></i> Activity for {{ $monthName }}</h4>
                                                </div>

                                                <table class="w-full text-sm">
                                                    <thead class="text-gray-400 border-b">
                                                        <tr>
                                                            <th class="pb-2 text-{{ $isRtl ? 'right' : 'left' }}">{{ __('messages.date') }}</th>
                                                            <th class="pb-2 text-{{ $isRtl ? 'right' : 'left' }}">{{ __('messages.weight') }}</th>
                                                            <th class="pb-2 text-{{ $isRtl ? 'right' : 'left' }}">{{ __('messages.price_per_kg') }}</th>
                                                            <th class="pb-2 text-{{ $isRtl ? 'right' : 'left' }}">{{ __('messages.total_price') }}</th>
                                                            <th class="pb-2 text-{{ $isRtl ? 'right' : 'left' }}">{{ __('messages.remaining') }}</th>
                                                            <th class="pb-2 text-center">Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="divide-y">
                                                        <!-- 1. SHOW BEGINNING BALANCE ROW IF EXISTS -->
                                                        @if(($type->beginning_weight ?? 0) > 0)
                                                        <tr class="bg-yellow-50 text-yellow-800 font-bold">
                                                            <td class="py-3 px-2"><i class="fa-solid fa-clock-rotate-left"></i></td>
                                                            <td class="py-3">{{ number_format($type->beginning_weight, 2) }} kg</td>
                                                            <td class="py-3" colspan="2">{{ __('messages.beginning_balance') }}</td>
                                                            <td class="py-3">-</td>
                                                            <td class="py-3 text-center">-</td>
                                                        </tr>
                                                        @endif

                                                        <!-- 2. CURRENT BATCHES -->
                                                        @foreach(($type->current_batches ?? []) as $batch)
                                                            <tr class="hover:bg-gray-50">
                                                                <td class="py-3 text-gray-700 font-medium">
                                                                    {{ $batch->batch_date }} 
                                                                </td>
                                                                <td class="py-3 font-bold text-[#556B2F]">+ {{ $batch->weight_kg }}</td>
                                                                <td class="py-3 text-gray-500">{{ number_format($batch->price_per_kg, 2) }}</td>
                                                                <td class="py-3 font-bold text-[#3E2723]">{{ number_format($batch->total_cost, 2) }}</td>
                                                                
                                                                <td class="py-3">
                                                                    @if($batch->remaining_amount > 0)
                                                                        <div class="flex items-center gap-2">
                                                                            <span class="font-bold text-red-600">{{ number_format($batch->remaining_amount, 2) }}</span>
                                                                            <button onclick="openPayModal({{ $batch->id }}, {{ $batch->remaining_amount }})" class="bg-green-100 text-green-700 hover:bg-green-200 px-2 py-1 rounded text-xs font-bold transition">Pay</button>
                                                                        </div>
                                                                    @else
                                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                                            <i class="fa-solid fa-check mr-1"></i> Paid
                                                                        </span>
                                                                    @endif
                                                                </td>

                                                                <td class="py-3 text-center flex justify-center gap-3">
                                                                    <button onclick="openBatchModal('edit', {{ $batch }})" class="text-blue-500 hover:text-blue-700"><i class="fa-solid fa-pen-to-square"></i></button>
                                                                    <form action="{{ route('green-coffee.destroyBatch', $batch->id) }}" method="POST" onsubmit="return confirm('Delete this batch?');">
                                                                        @csrf @method('DELETE')
                                                                        <button type="submit" class="text-red-400 hover:text-red-600"><i class="fa-solid fa-trash-can"></i></button>
                                                                    </form>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ================= MODALS SECTION ================= -->

        <!-- MODAL 1: ADD/EDIT TYPE -->
        <div x-show="showTypeModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 text-center">
                <div class="fixed inset-0 modal-backdrop transition-opacity" @click="showTypeModal = false"></div>
                
                <div class="bg-white rounded-xl shadow-2xl transform transition-all sm:max-w-md sm:w-full z-10 overflow-hidden text-left">
                    <div class="bg-[#3E2723] px-6 py-4 flex justify-between items-center text-white">
                        <h3 id="typeModalTitle" class="font-bold brand-font text-lg"></h3>
                        <button @click="showTypeModal = false" class="hover:text-gray-300"><i class="fa-solid fa-xmark fa-lg"></i></button>
                    </div>
                    <div class="p-6">
                        <form id="typeForm" method="POST">
                            @csrf <input type="hidden" name="_method" id="typeMethod" value="POST">
                            
                            <label class="block text-gray-700 font-bold mb-2 text-sm uppercase">{{ __('messages.coffee_type') }}</label>
                            <input type="text" name="name" id="typeNameInput" placeholder="e.g. Brazilian Santos" class="w-full border-gray-300 rounded-lg p-3 focus:ring-[#3E2723] focus:border-[#3E2723]" required>
                            
                            <div class="mt-6">
                                <button type="submit" class="w-full btn-premium btn-brown py-3 justify-center">{{ __('messages.save') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL 2: ADD/EDIT BATCH -->
        <div x-show="showBatchModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 text-center">
                <div class="fixed inset-0 modal-backdrop transition-opacity" @click="showBatchModal = false"></div>
                
                <div class="bg-white rounded-xl shadow-2xl transform transition-all sm:max-w-lg sm:w-full z-10 overflow-hidden text-left">
                    <div class="bg-[#556B2F] px-6 py-4 flex justify-between items-center text-white">
                        <h3 id="batchModalTitle" class="font-bold brand-font text-lg"></h3>
                        <button @click="showBatchModal = false" class="hover:text-gray-200"><i class="fa-solid fa-xmark fa-lg"></i></button>
                    </div>

                    <div class="p-6" dir="{{ $dir }}">
                        <form id="batchForm" method="POST">
                            @csrf <input type="hidden" name="_method" id="batchMethod" value="POST">
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">{{ __('messages.coffee_type') }}</label>
                                    <select name="green_coffee_type_id" id="batchTypeSelect" class="w-full border-gray-300 rounded-lg focus:ring-[#556B2F] focus:border-[#556B2F]" required>
                                        <option value="" disabled selected>{{ __('messages.choose_type') }}</option>
                                        @foreach($types as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">{{ __('messages.date') }}</label>
                                        <input type="date" name="batch_date" id="batchDate" value="{{ date('Y-m-d') }}" class="w-full border-gray-300 rounded-lg focus:ring-[#556B2F]">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">{{ __('messages.time') }}</label>
                                        <input type="time" name="batch_time" id="batchTime" value="{{ date('H:i') }}" class="w-full border-gray-300 rounded-lg focus:ring-[#556B2F]">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">{{ __('messages.weight') }}</label>
                                    <div class="relative">
                                        <input type="number" step="0.01" id="modal_weight_kg" name="weight_kg" class="w-full border-gray-300 rounded-lg pl-4 focus:ring-[#556B2F]" required>
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none font-bold text-gray-400">KG</div>
                                    </div>
                                </div>

                                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                    <div class="price-group">
                                        <div class="price-wrapper">
                                            <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">{{ __('messages.price_per_kg') }}</label>
                                            <input type="number" step="0.01" id="modal_price_per_kg" name="price_per_kg" class="w-full border-gray-300 rounded-lg text-sm focus:ring-[#556B2F]">
                                        </div>
                                        <div class="equals-sign">=</div>
                                        <div class="price-wrapper">
                                            <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">{{ __('messages.total_price') }}</label>
                                            <input type="number" step="0.01" id="modal_total_cost" name="total_cost" class="w-full border-gray-300 rounded-lg text-sm font-bold text-gray-800 focus:ring-[#556B2F]">
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-2 border-t border-gray-100">
                                    <label class="block text-xs font-bold text-[#556B2F] uppercase mb-1">{{ __('messages.paid_now') }}</label>
                                    <div class="relative">
                                        <input type="number" step="0.01" name="paid_amount" placeholder="0.00" class="w-full border-green-200 bg-green-50 rounded-lg focus:ring-[#556B2F]">
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none font-bold text-green-700">EGP</div>
                                    </div>
                                    <p class="text-[10px] text-gray-400 mt-1">Leave empty for Deferred Payment (آجل)</p>
                                </div>
                            </div>

                            <div class="mt-6">
                                <button type="submit" class="w-full btn-premium btn-green py-3 justify-center">{{ __('messages.save') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL 3: SETTLE PAYMENT -->
        <div x-show="showPayModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 text-center">
                <div class="fixed inset-0 modal-backdrop transition-opacity" @click="showPayModal = false"></div>
                
                <div class="bg-white rounded-xl shadow-2xl transform transition-all sm:max-w-sm sm:w-full z-10 overflow-hidden text-left">
                    <div class="bg-gray-800 px-6 py-4 flex justify-between items-center text-white">
                        <h3 class="font-bold brand-font text-lg"><i class="fa-solid fa-hand-holding-dollar"></i> {{ __('messages.settle_modal_title') }}</h3>
                        <button @click="showPayModal = false" class="hover:text-gray-300"><i class="fa-solid fa-xmark fa-lg"></i></button>
                    </div>
                    <div class="p-6">
                        <form action="{{ route('green-coffee.storePayment') }}" method="POST">
                            @csrf <input type="hidden" name="batch_id" id="payBatchId">
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">{{ __('messages.date') }}</label>
                                    <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" class="w-full border-gray-300 rounded-lg">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">{{ __('messages.amount_to_pay') }}</label>
                                    <div class="relative">
                                        <input type="number" step="0.01" name="amount" id="payAmount" class="w-full border-red-200 bg-red-50 rounded-lg text-red-700 font-bold text-lg focus:ring-red-500" required>
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-red-400">EGP</div>
                                    </div>
                                    <p class="text-xs text-gray-400 mt-1 text-right">Max Owed: <span id="maxOwedDisplay" class="font-bold text-gray-700"></span></p>
                                </div>
                            </div>
                            
                            <div class="mt-6">
                                <button type="submit" class="w-full btn-premium btn-dark py-3 justify-center">{{ __('messages.save') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL 4: FINANCIAL REPORT -->
        <div x-show="showFinancialModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 modal-backdrop transition-opacity" @click="showFinancialModal = false"></div>
                
                <div class="bg-white rounded-xl shadow-2xl transform w-full max-w-4xl z-10 overflow-hidden flex flex-col max-h-[90vh]">
                    <!-- Header -->
                    <div class="bg-gray-900 px-6 py-4 flex justify-between items-center text-white shrink-0">
                        <h3 class="font-bold brand-font text-xl"><i class="fa-solid fa-scale-balanced"></i> {{ __('messages.financial_report') }}</h3>
                        <button @click="showFinancialModal = false" class="hover:text-gray-300"><i class="fa-solid fa-xmark fa-lg"></i></button>
                    </div>

                    <!-- Content -->
                    <div class="p-6 overflow-y-auto custom-scrollbar" dir="{{ $dir }}" x-data="{ activeTab: 'summary' }">
                        
                        <!-- Top Stats Summary -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                            <div class="p-4 bg-gray-50 rounded-lg border text-center">
                                <p class="text-[10px] font-bold text-gray-400 uppercase">{{ __('messages.total_inventory_cost') }}</p>
                                <p class="text-xl font-bold text-gray-800 brand-font mt-1">{{ number_format($grandTotalCost, 2) }}</p>
                            </div>
                            <div class="p-4 bg-green-50 rounded-lg border border-green-100 text-center">
                                <p class="text-[10px] font-bold text-green-600 uppercase">{{ __('messages.total_paid_global') }}</p>
                                <p class="text-xl font-bold text-green-700 brand-font mt-1">{{ number_format($grandTotalPaid, 2) }}</p>
                            </div>
                            <div class="p-4 bg-red-50 rounded-lg border border-red-100 text-center">
                                <p class="text-[10px] font-bold text-red-500 uppercase">{{ __('messages.total_debt_global') }}</p>
                                <p class="text-xl font-bold text-red-600 brand-font mt-1">{{ number_format($grandTotalDebt, 2) }}</p>
                            </div>
                        </div>

                        <!-- Tabs -->
                        <div class="flex border-b border-gray-200 mb-6">
                            <button @click="activeTab = 'summary'" :class="{ 'border-b-2 border-gray-800 text-gray-800': activeTab === 'summary', 'text-gray-400 hover:text-gray-600': activeTab !== 'summary' }" class="py-3 px-6 font-bold transition">
                                <i class="fa-solid fa-list"></i> {{ __('messages.payment_history') }}
                            </button>
                            <button @click="activeTab = 'debt'" :class="{ 'border-b-2 border-red-600 text-red-600': activeTab === 'debt', 'text-gray-400 hover:text-gray-600': activeTab !== 'debt' }" class="py-3 px-6 font-bold transition">
                                <i class="fa-solid fa-circle-exclamation"></i> {{ __('messages.unpaid_batches') }}
                            </button>
                        </div>

                        <!-- TAB 1: HISTORY -->
                        <div x-show="activeTab === 'summary'" x-transition.opacity>
                            @if(count($allPayments) > 0)
                                <table class="w-full text-sm">
                                    <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                                        <tr>
                                            <th class="p-3 text-{{ $isRtl ? 'right' : 'left' }}">{{ __('messages.date') }}</th>
                                            <th class="p-3 text-{{ $isRtl ? 'right' : 'left' }}">{{ __('messages.payment_for') }}</th>
                                            <th class="p-3 text-{{ $isRtl ? 'right' : 'left' }}">{{ __('messages.total_value') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y">
                                        @foreach($allPayments as $payment)
                                            <tr class="hover:bg-gray-50">
                                                <td class="p-3 font-medium">{{ $payment['date'] }}</td>
                                                <td class="p-3">
                                                    <span class="font-bold text-gray-800">{{ $payment['type_name'] }}</span>
                                                    <span class="text-xs text-gray-400 block">{{ __('messages.batch_from') }}: {{ $payment['batch_date'] }}</span>
                                                </td>
                                                <td class="p-3 font-bold text-green-700">+ {{ number_format($payment['amount'], 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p class="text-center text-gray-400 py-8">{{ __('messages.no_payments') }}</p>
                            @endif
                        </div>

                        <!-- TAB 2: DEBT -->
                        <div x-show="activeTab === 'debt'" style="display: none;" x-transition.opacity>
                            @if(count($debtBatches) > 0)
                                <table class="w-full text-sm">
                                    <thead class="bg-red-50 text-red-800 uppercase text-xs">
                                        <tr>
                                            <th class="p-3 text-{{ $isRtl ? 'right' : 'left' }}">{{ __('messages.coffee_type') }}</th>
                                            <th class="p-3 text-{{ $isRtl ? 'right' : 'left' }}">{{ __('messages.date') }}</th>
                                            <th class="p-3 text-{{ $isRtl ? 'right' : 'left' }}">{{ __('messages.total_price') }}</th>
                                            <th class="p-3 text-{{ $isRtl ? 'right' : 'left' }}">{{ __('messages.remaining') }}</th>
                                            <th class="p-3"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y">
                                        @foreach($debtBatches as $batch)
                                            <tr class="hover:bg-red-50">
                                                <td class="p-3 font-bold">{{ $batch->type->name }}</td>
                                                <td class="p-3 text-gray-600">{{ $batch->batch_date }}</td>
                                                <td class="p-3 text-gray-500">{{ number_format($batch->total_cost, 2) }}</td>
                                                <td class="p-3 font-bold text-red-600">{{ number_format($batch->remaining_amount, 2) }}</td>
                                                <td class="p-3 text-center">
                                                    <button @click="showFinancialModal = false; openPayModal({{ $batch->id }}, {{ $batch->remaining_amount }})" class="bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-red-700 shadow-sm">
                                                        {{ __('messages.pay_btn') }}
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="text-center py-12">
                                    <div class="h-16 w-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <i class="fa-solid fa-check text-green-600 text-2xl"></i>
                                    </div>
                                    <p class="text-gray-600 font-bold">{{ __('messages.no_debt') }}</p>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- JAVASCRIPT LOGIC -->
    <script>
        // TYPE MODAL
        function openTypeModal(mode, id = null, name = '') {
            const form = document.getElementById('typeForm');
            const title = document.getElementById('typeModalTitle');
            const input = document.getElementById('typeNameInput');
            const method = document.getElementById('typeMethod');

            if (mode === 'create') {
                title.innerHTML = '<i class="fa-solid fa-plus"></i> ' + "{{ __('messages.add_new_type') }}";
                form.action = "{{ route('green-coffee.storeType') }}";
                method.value = "POST";
                input.value = "";
            } else {
                title.innerHTML = '<i class="fa-solid fa-pen"></i> Edit Type';
                form.action = "/green-coffee/update-type/" + id;
                method.value = "PUT";
                input.value = name;
            }
            window.dispatchEvent(new CustomEvent('open-type-modal'));
        }

        // BATCH MODAL
        function openBatchModal(mode, batch = null) {
            const form = document.getElementById('batchForm');
            const title = document.getElementById('batchModalTitle');
            const method = document.getElementById('batchMethod');
            const typeSelect = document.getElementById('batchTypeSelect');
            
            // Inputs
            const dateIn = document.getElementById('batchDate');
            const timeIn = document.getElementById('batchTime');
            const weightIn = document.getElementById('modal_weight_kg');
            const priceIn = document.getElementById('modal_price_per_kg');
            const totalIn = document.getElementById('modal_total_cost');

            if (mode === 'create') {
                title.innerHTML = '<i class="fa-solid fa-box-open"></i> ' + "{{ __('messages.add_inventory_title') }}";
                form.action = "{{ route('green-coffee.storeBatch') }}";
                method.value = "POST";
                if(typeSelect) typeSelect.value = "";
                if(weightIn) weightIn.value = "";
                if(priceIn) priceIn.value = "";
                if(totalIn) totalIn.value = "";
            } else {
                title.innerHTML = '<i class="fa-solid fa-pen-to-square"></i> Edit Inventory';
                form.action = "/green-coffee/update-batch/" + batch.id;
                method.value = "PUT";
                if(typeSelect) typeSelect.value = batch.green_coffee_type_id;
                if(dateIn) dateIn.value = batch.batch_date;
                if(timeIn) timeIn.value = batch.batch_time;
                if(weightIn) weightIn.value = batch.weight_kg;
                if(priceIn) priceIn.value = batch.price_per_kg;
                if(totalIn) totalIn.value = batch.total_cost;
            }
            window.dispatchEvent(new CustomEvent('open-batch-modal'));
        }

        // PAYMENT MODAL
        function openPayModal(id, owedAmount) {
            document.getElementById('payBatchId').value = id;
            document.getElementById('payAmount').value = owedAmount;
            document.getElementById('maxOwedDisplay').innerText = owedAmount;
            window.dispatchEvent(new CustomEvent('open-pay-modal'));
        }

        // AUTO CALCULATION
        const mWeightInput = document.getElementById('modal_weight_kg');
        const mPricePerKgInput = document.getElementById('modal_price_per_kg');
        const mTotalCostInput = document.getElementById('modal_total_cost');

        if(mPricePerKgInput && mWeightInput){
            mPricePerKgInput.addEventListener('input', function() {
                const weight = parseFloat(mWeightInput.value);
                const price = parseFloat(this.value);
                if(weight && price) mTotalCostInput.value = (weight * price).toFixed(2);
            });
        }

        if(mTotalCostInput && mWeightInput){
            mTotalCostInput.addEventListener('input', function() {
                const weight = parseFloat(mWeightInput.value);
                const total = parseFloat(this.value);
                if(weight && total) mPricePerKgInput.value = (total / weight).toFixed(2);
            });
        }
    </script>
</x-app-layout>