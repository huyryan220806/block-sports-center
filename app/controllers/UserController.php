<?php

class UserController extends Controller
{
    public function index()
    {
        $data['title'] = 'Trang người dùng';
        $this->view('user/index', $data);
    }

    // Ví dụ thêm 1 trang khác
    public function booking()
    {
        $data['title'] = 'Đặt sân / đặt phòng';
        $this->view('user/booking', $data);
    }
}
//