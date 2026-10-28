<?php

namespace App\Application\Ecommerce\Controllers;

use App\Application\Marketplace\Enums\MerchantOrderHistoryStatuses;
use App\Application\Marketplace\Enums\MerchantOrderStatuses;
use Domain\EducationalRequest\Models\EducationalRequest;
use App\Domain\User\Models\UserAddress;
use App\Domain\MarketPlace\Models\MerchantOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Domain\AppBase\Models\Location\Country;
use App\Domain\AppBase\Models\Location\City;
use App\Domain\AppBase\Models\Location\Distract;
use App\Domain\MarketPlace\Models\MerchantOrderHistory;

class AccountController extends Controller
{
    public function index()
    {
        return $this->profile();
    }

    public function staticIndex(Request $request)
    {
        $user = Auth::user();
        $section = $request->get('section', 'profile');
        $data = compact('user', 'section');

        if ($section === 'orders') {
            $data['orders'] = $user->orders()->latest()->paginate(10)->appends($request->query());
        } elseif ($section === 'service_requests') {
            $data['serviceRequests'] = EducationalRequest::where('requester_id', $user->id)
                ->with('academicMajor')
                ->withCount('attachments')
                ->latest()
                ->paginate(10)
                ->appends($request->query());
        } elseif ($section === 'addresses') {
            $data['addresses'] = $user->addresses;
        } else {
            $data['section'] = 'profile';
        }

        if ($section !== 'profile') {
            return view('ecommerce::pages.account.account-section-page', $data);
        }

        return view('ecommerce::pages.account.account', $data);
    }

    public function profile()
    {
        $user = Auth::user();
        if (request()->header('X-Up-Target') == '.dashboard-content') {
            return view('ecommerce::pages.account._profile', compact('user'));
        }
        return view('ecommerce::pages.account.dashboard', ['page' => 'profile', 'user' => $user]);
    }

    public function orders()
    {
        $user = Auth::user();
        $orders = $user->orders()->latest()->paginate(10);
        if (request()->header('X-Up-Target') == '.dashboard-content') {
            return view('ecommerce::pages.account._orders', compact('orders'));
        }
        return view('ecommerce::pages.account.account-section-page', [
            'section' => 'orders',
            'user' => $user,
            'orders' => $orders,
        ]);
    }

    public function order($id)
    {
        $user = Auth::user();
        $order = $user->orders()->with('orderItems')->findOrFail($id);

        if (request()->header('X-Up-Target') == '.dashboard-content') {
            return view('ecommerce::pages.account._order_details', compact('order'));
        }
        return view('ecommerce::pages.account.account-section-page', [
            'section' => 'order_details',
            'user' => $user,
            'order' => $order,
        ]);
    }

    public function cancelOrder(Request $request, $id)
    {
        $user = Auth::user();
        $order = $user->orders()->findOrFail($id);
        $statusVal = $order->status instanceof \UnitEnum ? $order->status->value : $order->status;

        $forbiddenStatuses = [
            MerchantOrderStatuses::READY->value,
            MerchantOrderStatuses::CANCELLED->value,
            MerchantOrderStatuses::ON_WAY->value,
            MerchantOrderStatuses::EXPIRED->value,
            MerchantOrderStatuses::PREVIOUS->value,
            MerchantOrderStatuses::CURRENT->value
        ];

        if (in_array($statusVal, $forbiddenStatuses)) {
            return back()->with('error', __('ecommerce::ecommerce.cannot_cancel_order_in_progress'));
        }

        $order->update(['status' => MerchantOrderStatuses::CANCELLED->value]);

        if ($order->orderItems) {
            foreach ($order->orderItems as $orderItem) {
                if ($orderItem->item && !is_null($orderItem->item->stock)) {
                    $orderItem->item->increment('stock', $orderItem->qty);
                }
            }
        }

        $cancelReason = $request->input('cancellation_reason') ?: __('ecommerce::ecommerce.user_cancelled_reason');

        MerchantOrderHistory::create([
            'order_id' => $order->id,
            'action' => MerchantOrderHistoryStatuses::USER_CANCELLED->value,
            'reason' => $cancelReason,
            'changeable_type' => get_class($user),
            'changeable_id' => $user->id,
        ]);

        return back()->with('success', __('ecommerce::ecommerce.order_cancelled_successfully'));
    }

    public function serviceRequests()
    {
        $user = Auth::user();
        $serviceRequests = EducationalRequest::where('requester_id', $user->id)
            ->with('academicMajor')
            ->withCount('attachments')
            ->latest()
            ->paginate(10);

        if (request()->header('X-Up-Target') == '.dashboard-content') {
            return view('ecommerce::pages.account._service_requests', compact('serviceRequests'));
        }
        return view('ecommerce::pages.account.dashboard', ['page' => 'service_requests', 'user' => $user, 'serviceRequests' => $serviceRequests]);
    }

    public function serviceRequest($id)
    {
        $user = Auth::user();
        $serviceRequest = EducationalRequest::where('requester_id', $user->id)
            ->with(['academicMajor', 'files'])
            ->findOrFail($id);

        if (request()->header('X-Up-Target') == '.dashboard-content') {
            return view('ecommerce::pages.account._service_request_details', compact('serviceRequest'));
        }
        return view('ecommerce::pages.account.dashboard', ['page' => 'service_request_details', 'user' => $user, 'serviceRequest' => $serviceRequest]);
    }

    public function addresses()
    {
        $user = Auth::user();
        $addresses = $user->addresses;
        if (request()->header('X-Up-Target') == '.dashboard-content') {
            return view('ecommerce::pages.account._addresses', compact('addresses'));
        }
        return view('ecommerce::pages.account.dashboard', ['page' => 'addresses', 'user' => $user, 'addresses' => $addresses]);
    }

    public function editProfile()
    {
        $user = Auth::user();
        if (request()->header('X-Up-Target') == '.dashboard-content') {
            return view('ecommerce::pages.account._edit_profile', compact('user'));
        }
        return view('ecommerce::pages.account.account-section-page', [
            'section' => 'edit_profile',
            'user' => $user,
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
        ]);

        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'date_of_birth' => $request->date_of_birth,
        ]);

        if ($request->filled('current_password')) {
            $request->validate([
                'current_password' => 'required',
                'password' => 'required|string|min:8|confirmed',
            ]);

            if (!\Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => __('ecommerce::ecommerce.current_password_incorrect')]);
            }

            $user->update(['password' => \Hash::make($request->password)]);
        }

        if (request()->header('X-Up-Target') == '.dashboard-content') {
            return view('ecommerce::pages.account._profile', compact('user'))
                ->with('success', __('ecommerce::ecommerce.profile_updated'));
        }

        return redirect()->route('account.index')->with('success', __('ecommerce::ecommerce.profile_updated'));
    }

    public function createAddress()
    {
        $user = Auth::user();
        $countries = Country::all();
        $cities = City::all();
        $districts = Distract::all();

        if (request()->header('X-Up-Target') == '.dashboard-content') {
            return view('ecommerce::pages.account._create_address', compact('user', 'countries', 'cities', 'districts'));
        }
        return view('ecommerce::pages.account.dashboard', [
            'page' => 'create_address',
            'user' => $user,
            'countries' => $countries,
            'cities' => $cities,
            'districts' => $districts
        ]);
    }

    public function storeAddress(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'country_id' => 'required|exists:countries,id',
            'city_id' => 'required|exists:cities,id',
            'distract_id' => 'required|exists:distracts,id',
            'address' => 'required|string',
        ]);

        $user = Auth::user();

        if ($request->is_default) {
            $user->addresses()->update(['is_default' => false]);
        }

        $user->addresses()->create([
            'title' => $request->title,
            'country_id' => $request->country_id,
            'city_id' => $request->city_id,
            'distract_id' => $request->distract_id,
            'address' => $request->address,
            'latitude' => $request->latitude ?? '0',
            'longitude' => $request->longitude ?? '0',
            'is_default' => $request->is_default ?? false,
        ]);

        $addresses = $user->addresses;

        if (request()->header('X-Up-Target') == '.dashboard-content') {
            return view('ecommerce::pages.account._addresses', compact('addresses'))
                ->with('success', __('ecommerce::ecommerce.address_added'));
        }

        return redirect()->route('account.addresses')->with('success', __('ecommerce::ecommerce.address_added'));
    }

    public function editAddress($id)
    {
        $user = Auth::user();
        $address = $user->addresses()->findOrFail($id);
        $countries = Country::all();
        $cities = City::all();
        $districts = Distract::all();

        if (request()->header('X-Up-Target') == '.dashboard-content') {
            return view('ecommerce::pages.account._edit_address', compact('address', 'user', 'countries', 'cities', 'districts'));
        }

        return view('ecommerce::pages.account.dashboard', [
            'page' => 'edit_address',
            'address' => $address,
            'user' => $user,
            'countries' => $countries,
            'cities' => $cities,
            'districts' => $districts
        ]);
    }

    public function updateAddress(Request $request, $id)
    {
        $user = Auth::user();
        $address = $user->addresses()->findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'country_id' => 'required|exists:countries,id',
            'city_id' => 'required|exists:cities,id',
            'distract_id' => 'required|exists:distracts,id',
            'address' => 'required|string',
        ]);

        if ($request->is_default) {
            $user->addresses()->update(['is_default' => false]);
        }

        $address->update([
            'title' => $request->title,
            'country_id' => $request->country_id,
            'city_id' => $request->city_id,
            'distract_id' => $request->distract_id,
            'address' => $request->address,
            'latitude' => $request->latitude ?? $address->latitude,
            'longitude' => $request->longitude ?? $address->longitude,
            'is_default' => $request->is_default ?? false,
        ]);

        $addresses = $user->addresses;

        if (request()->hasHeader('X-Up-Target')) {
            return view('ecommerce::pages.account._addresses', compact('addresses'))
                ->with('success', __('ecommerce::ecommerce.address_updated'));
        }

        return redirect()->route('account.addresses')->with('success', __('ecommerce::ecommerce.address_updated'));
    }

    public function deleteAddress($id)
    {
        $user = Auth::user();
        $address = $user->addresses()->findOrFail($id);

        if ($address->is_default && $user->addresses()->count() > 1) {
            $user->addresses()->where('id', '!=', $id)->first()->update(['is_default' => true]);
        }

        $address->delete();

        $addresses = $user->addresses;

        if (request()->hasHeader('X-Up-Target')) {
            return view('ecommerce::pages.account._addresses', compact('addresses'))
                ->with('success', __('ecommerce::ecommerce.address_deleted'));
        }

        return redirect()->route('account.addresses')->with('success', __('ecommerce::ecommerce.address_deleted'));
    }
}
