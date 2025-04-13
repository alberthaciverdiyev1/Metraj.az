<div class="others-section-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="theme-btn1 open-search-filter-form">
                    <p class="open-text">Open Search Form
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M18.031 16.6168L22.3137 20.8995L20.8995 22.3137L16.6168 18.031C15.0769 19.263 13.124 20 11 20C6.032 20 2 15.968 2 11C2 6.032 6.032 2 11 2C15.968 2 20 6.032 20 11C20 13.124 19.263 15.0769 18.031 16.6168ZM16.0247 15.8748C17.2475 14.6146 18 12.8956 18 11C18 7.1325 14.8675 4 11 4C7.1325 4 4 7.1325 4 11C4 14.8675 7.1325 18 11 18C12.8956 18 14.6146 17.2475 15.8748 16.0247L16.0247 15.8748Z"></path>
                        </svg>
                    </p>
                    <p class="close-text">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M10.5859 12L2.79297 4.20706L4.20718 2.79285L12.0001 10.5857L19.793 2.79285L21.2072 4.20706L13.4143 12L21.2072 19.7928L19.793 21.2071L12.0001 13.4142L4.20718 21.2071L2.79297 19.7928L10.5859 12Z"></path>
                        </svg>
                        Close
                    </p>
                </div>
                <div class="property-tab-section search-filter-form">
                    <div class="tab-header">
                        <button class="tab-btn active">@lang('All')</button>
                        <button class="tab-btn">@lang("New")</button>
                        <button class="tab-btn">@lang("Used")</button>
                    </div>

                    <div class="tab-content1">
                        <div class="filters">

                            <livewire:webui::brand-select/>

                            <div class="filter-group">
                                <label>Labels</label>
                                <select>
                                    <option>All Labels</option>
                                    <option>New Offer</option>
                                    <option>Hot Offer</option>
                                </select>
                            </div>

                            <div class="filter-group">
                                <livewire:webui::city-select/>
                            </div>


                            <div class="filter-group">
                                <label for="customize-sale">Customize</label>
                                <button id="customize-sale" class="customize-sale show-form">
                                    Advance <span class="icon">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                    viewBox="0 0 24 24" fill="currentColor">
                                     <path d="M6.17071 18C6.58254 16.8348 7.69378 16 9 16C10.3062 16 11.4175 16.8348 11.8293 18H22V20H11.8293C11.4175 21.1652 10.3062 22 9 22C7.69378 22 6.58254 21.1652 6.17071 20H2V18H6.17071ZM12.1707 11C12.5825 9.83481 13.6938 9 15 9C16.3062 9 17.4175 9.83481 17.8293 11H22V13H17.8293C17.4175 14.1652 16.3062 15 15 15C13.6938 15 12.5825 14.1652 12.1707 13H2V11H12.1707ZM6.17071 4C6.58254 2.83481 7.69378 2 9 2C10.3062 2 11.4175 2.83481 11.8293 4H22V6H11.8293C11.4175 7.16519 10.3062 8 9 8C7.69378 8 6.58254 7.16519 6.17071 6H2V4H6.17071Z"></path>
                                  </svg></span>
                                </button>
                            </div>
                            <div class="search-button">
                                <button id="search-sale">Search
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                        <path
                                            d="M18.031 16.6168L22.3137 20.8995L20.8995 22.3137L16.6168 18.031C15.0769 19.263 13.124 20 11 20C6.032 20 2 15.968 2 11C2 6.032 6.032 2 11 2C15.968 2 20 6.032 20 11C20 13.124 19.263 15.0769 18.031 16.6168ZM16.0247 15.8748C17.2475 14.6146 18 12.8956 18 11C18 7.1325 14.8675 4 11 4C7.1325 4 4 7.1325 4 11C4 14.8675 7.1325 18 11 18C12.8956 18 14.6146 17.2475 15.8748 16.0247L16.0247 15.8748Z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                    </div>

                    <div class="wd-search-form ">
                        <div class=" group-select">
                            <div class="mb-3">
                                <h5 class="mb-3">@lang('Price')</h5>
                                <div class="input-group h-75">
                                    <input type="text" class="form-control" placeholder="Min">
                                    <input type="text" class="form-control" placeholder="Max">
                                </div>
                            </div>

                            <livewire:webui::currency-select/>

                            <livewire:webui::body-type-select/>

                            <livewire:webui::fuel-type-select/>

                            <livewire:webui::gear-select/>

                            <livewire:webui::gearbox-select/>


                            <div class="home-filters-btn">
                                <button id="is_credit">@lang('Credit')</button>
                                <button id="is_barter">@lang('Barter')</button>
                            </div>


                        </div>
                        <div class="group-checkbox">
                            <div class=" title text-4 fw-6">Others Features</div>
                            <div class="space16"></div>

                            <livewire:webui::feature-list/>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
