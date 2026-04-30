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

            window.location.href = "thanhtoan.php";
        });
    }
});