$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('.delete-button').on('click', function (e) {
        e.preventDefault();
        const url = $(this).data('url');

        if (confirm('Bạn có chắc chắn muốn xóa?')) {
            $.ajax({
                url: url,
                type: 'DELETE',
                success: function (response) {
                    alert('Xóa thành công!');
                    location.reload();
                },
                error: function (xhr) {
                    alert('Đã xảy ra lỗi: ' + xhr.responseText);
                }
            });
        }
    });
});