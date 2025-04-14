@extends('webui::main')

@section('webui::content')
    <div class="contact-inner-section sp1">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12 mt-xxl-5">
                    <div class="contact-form-area">
                        <div class="row d-flex">
                            <livewire:webui::brand-select :type="'form'"/>
                            <livewire:webui::city-select
                                :type="'form'"
                                :class="'col-lg-6 mb-3 mt-3'"
                            />
                            <livewire:webui::currency-select :type="'form'"/>
                            <livewire:webui::feature-list :type="'form'"/>
                            <livewire:webui::gear-select :type="'form'"/>
                            <livewire:webui::gearbox-select :type="'form'"/>
                            <livewire:webui::fuel-type-select :type="'form'"/>
                            <livewire:webui::body-type-select :type="'form'"/>


                            <div class="col-lg-12">
                                <div class="input-area">
                                    <input type="text" placeholder="Last Name">
                                </div>
                            </div>


                            <div class="col-lg-12">
                                <div class="input-area">
                                    <textarea placeholder="Your Message"></textarea>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="input-area">
                                    <button type="submit" class="theme-btn1">Send Now <span class="arrow1"><svg
                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24"
                                                height="24" fill="currentColor">
                        <path d="M12 13H4V11H12V4L20 12L12 20V13Z"></path>
                      </svg></span><span class="arrow2"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                             width="24" height="24" fill="currentColor">
                        <path d="M12 13H4V11H12V4L20 12L12 20V13Z"></path>
                      </svg></span></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
