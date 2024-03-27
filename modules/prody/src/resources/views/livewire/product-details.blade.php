<div class="border border-dark">
    
    {{-- Section Tabs --}}
    <ul class="section-tabs nav nav-pills nav-fill" id="productSectionTabs" role="tablist">
        @foreach ($sections as $section)
            <li class="nav-item {{ $loop->first ? '' : 'border-start' }}">
                @if ($section['slug'] == $currentSection)
                    <a class="nav-link fw-bold font-title active"
                        data-bs-toggle="collapse" 
                        href="#productSectionTabContent" role="button" aria-expanded="false" aria-controls="productSectionTabContent">
                        {{ $section['name'] }}
                    </a>
                @else                  
                    <a class="nav-link fw-bold font-title {{ $section['slug'] == $currentSection ? 'active' : '' }}"
                        href="{{ route('products.show', ['product' => $model->slug, 'section' => $section['slug']]) }}">
                        {{ $section['name'] }}
                    </a>
                @endif
            </li>
        @endforeach
    </ul>
   

    {{-- Section Contents --}}
    <div class="collapse show border-top" id="productSectionTabContent">
        <div class="tab-content" id="productSectionTabs">
            <div class="tab-pane fade show active" tabindex="0">
                @switch($currentSection)
                    @case('options')
                        @include('prody::includes.product-options')
                        @break

                    @case('ranges')
                        @include('prody::includes.product-ranges')
                        @break

                    @case('attributes')
                        @include('prody::includes.product-attributes')
                        @break

                    @case('measurements')
                        @include('prody::includes.product-measurements')
                        @break
                   
                    @case('collections')
                        @include('prody::includes.product-collections')
                        @break
                        
                    @default
                @endswitch
            </div>
        </div>
        <style scoped>
            .section-tabs.nav-pills .nav-item .nav-link {
                border-radius: 0px !important;
            }

            .section-tabs.nav-pills .nav-link {
                background-color: #f8f9fa;
                color: #212529;
            }

            .section-tabs.nav-pills .nav-link.active {
                color: #fff;
                background-color: #212529;
            }

            .section-tabs.nav-pills .nav-link:hover {
                color: #fff;
                background-color: #212529;
            }
        </style>
    </div>

</div>
