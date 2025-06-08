<div class="agent-info-section max-w-5xl mx-auto mt-3">
    <h2 class="agent-info-title">Agent Information</h2>
    <p class="agent-info-subtitle">Choose type agent information?</p>

    <div class="agent-info-options">
        @if(session('user'))
            <label class="agent-info-label">
                <input type="radio" name="agent-info" class="agent-info-radio" value="{{session('user')['id']}}"
                       checked/>
                <span class="agent-info-custom-radio">
                      <i class="bi bi-check agent-info-check-icon"></i>
                 </span>
                {{session('user')['name']}}
            </label>
        @else
            <div>
                <label for="advertiser" class="block font-semibold mb-1">Advertiser:*</label>
                <select name="advertiser"
                        class="w-full border border-gray-300 rounded-md px-4 py-2">
                    <option value="">Choose One</option>
                    <option value="user">User</option>
                    <option value="realtor">Realtor</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="advertiser_name" class="block font-semibold mb-1">Advertiser Name:*</label>
                <input type="text" name="advertiser_name" placeholder="Name"
                       class="w-full border border-gray-300 rounded-md px-4 py-2">
            </div>

        @endif

    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
        <div>
            <label for="phone_1" class="block font-semibold mb-1">@lang('Phone') 1</label>
            <input type="text" id="phone_1"
                   placeholder="Enter phone number"
                   class="w-full border border-gray-300 rounded-md px-4 py-2">
        </div>
        <div>
            <label for="phone_2" class="block font-semibold mb-1">@lang('Phone') 2</label>
            <input type="text" id="phone_2"
                   placeholder="Enter phone number"
                   class="w-full border border-gray-300 rounded-md px-4 py-2">
        </div>
        <div>
            <label for="phone_3" class="block font-semibold mb-1">@lang('Phone') 3</label>
            <input type="text" id="phone_3"
                   placeholder="Enter phone number"
                   class="w-full border border-gray-300 rounded-md px-4 py-2">
        </div>
        <div>
            <label for="phone_4" class="block font-semibold mb-1">@lang('Phone') 4</label>
            <input type="text" id="phone_4"
                   placeholder="Enter phone number"
                   class="w-full border border-gray-300 rounded-md px-4 py-2">
        </div>

    </div>
    <div class="mb-4">
        <label for="mail" class="block font-semibold mb-1">Email:*</label>
        <input type="mail" id="mail" placeholder="Email"
               class="w-full border border-gray-300 rounded-md px-4 py-2">
    </div>


</div>
