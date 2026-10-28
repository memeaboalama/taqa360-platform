<?php

namespace Application\Admin\Controllers;

use App\Domain\RefundPayment\Repositories\RefundPaymentRepository;
use App\Http\Controllers\Controller;

class RefundPaymentController extends Controller
{
    private $refundpaymentRepository;
    public function __construct(RefundPaymentRepository $refundpaymentRepository)
    {
        $this->refundpaymentRepository = $refundpaymentRepository;
    }

    public function index()
    {
        return view('admin::pages.refundpayment.index');
    }
}
