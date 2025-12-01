README - ĐỒ ÁN LẬP TRÌNH ỨNG DỤNG WEB
====================================

NHÓM: 01 (Lớp: 251_71ITSE30503_0301)
HỌC PHẦN: Lập Trình Ứng Dụng Web
Giảng viên: ThS. Trần Công Thanh

----------------------------------------------------------------------
I. THÔNG TIN THÀNH VIÊN
----------------------------------------------------------------------
- 2474802010140 - Nguyễn Đình Huy - Fullstacks
- 2474802010129 - Nguyễn Phi Hùng - Database + Triển khai (UI)
- 2474802010314 - Đỗ Hoàng Phúc - Backend (Models + Controllers)
- 2474802010313 - Huỳnh Hữu Phúc - Database + Models
- 2474802010386 - Lê Quyết Tiến - Frontend + Controllers

----------------------------------------------------------------------
II. MÔ TẢ ĐỀ TÀI
----------------------------------------------------------------------
Tên đề tài: QUẢN LÝ TRUNG TÂM THỂ DỤC THỂ THAO  BLOCK SPORTS CENTER

Mô tả ngắn:
Website mô phỏng hệ thống quản lý dữ liệu thực tế sử dụng  HTML5, CSS3, JavaScript, PHP và MySQL. Hỗ trợ chức năng đăng nhập, thêm/xóa/sửa/tìm kiếm dữ liệu và thống kê cơ bản.

----------------------------------------------------------------------
III. CÁCH CÀI ĐẶT & CHẠY DỰ ÁN (LOCALHOST - XAMPP)
----------------------------------------------------------------------
1. Cài đặt XAMPP
2. Copy toàn bộ thư mục SourceCode vào:
   htdocs/block-sports-center
3. Khởi động Apache và MySQL
4. Import Database:
   - Mở phpMyAdmin
   - Tạo database mới: block_sports_center (utf8_unicode_ci)
   - Import file: Database/block_sports_center.sql
5. Chạy dự án:
   http://localhost/block-sports-center

----------------------------------------------------------------------
IV. TÀI KHOẢN ĐĂNG NHẬP
----------------------------------------------------------------------
Ví dụ (cập nhật theo nhóm):
- HuyAD / HuyVN@123 (Admin)
- morningguy / goodnight (User)

----------------------------------------------------------------------
V. LINK TRIỂN KHAI ONLINE (FREE HOST)
----------------------------------------------------------------------
URL: https://block-sports-center-production.up.railway.app/

----------------------------------------------------------------------
VI. LINK GITHUB (BẮT BUỘC)
----------------------------------------------------------------------
Repo chính (public): 
https://github.com/huyryan220806/block-sports-center

Nhánh từng sinh viên (BẮT BUỘC):
- SV1: https://github.com/huyryan220806/block-sports-center/tree/main
- SV2: https://github.com/huyryan220806/block-sports-center/tree/hung-database
- SV3: https://github.com/huyryan220806/block-sports-center/tree/d.phuc-controllers
- SV4: https://github.com/huyryan220806/block-sports-center/tree/h.phuc-models
- SV5: https://github.com/huyryan220806/block-sports-center/tree/tien-ui

Ghi chú:
=> Mỗi thành viên phải có log commit rõ ràng xuyên suốt 3 tuần.
=> Không có log = không đạt đồ án (theo yêu cầu học phần).

----------------------------------------------------------------------
VII. CẤU TRÚC THƯ MỤC BÀI NỘP
----------------------------------------------------------------------
/DoAn_BlockSportsCenter
    /DoAn_BlockSportsCenter
        block-sports-center (Source Code)
	block_sports_center.sql (Database)
	BaoCao_Team01_0301.docx (Báo Cáo, gồm bảng phân công)
	README.txt
    BaoCao_BlockSportsCenter.pdf (Slide)

----------------------------------------------------------------------
VIII. GHI CHÚ QUAN TRỌNG
----------------------------------------------------------------------
- Website phải chạy trên XAMPP và free host.
- Database phải import được không lỗi.
- Mã nguồn phải có comment, đặt tên rõ ràng.
- Báo cáo 10–15 trang kèm sơ đồ chức năng + ERD.
- Slide thuyết trình chuẩn bị đúng hạn.
- Đảm bảo mỗi thành viên hiểu phần mình làm.
