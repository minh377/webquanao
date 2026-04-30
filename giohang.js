document.addEventListener("DOMContentLoaded", function () {

    // 1. CẢNH BÁO KHI XÓA SẢN PHẨM
    const removeButtons = document.querySelectorAll('.remove-btn');

    removeButtons.forEach(button => {
        button.addEventListener('click', function (event) {
            // Hiện bảng hỏi xác nhận
            const confirmDelete = confirm("Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?");

            // Nếu người dùng chọn "Cancel" (Hủy) thì chặn không cho xóa
            if (!confirmDelete) {
                event.preventDefault();
            }
        });
    });

    // 2. XỬ LÝ NÚT THANH TOÁN
    const checkoutBtn = document.querySelector('.btn-checkout');

    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', function () {
            alert("Tính năng thanh toán đang được phát triển! Cảm ơn bạn đã ủng hộ ShopTheMan.");
            // Sau này bạn làm trang thanh toán thì có thể thay bằng dòng lệnh: 
            // window.location.href = "thanhtoan.php";
        });
    }
});