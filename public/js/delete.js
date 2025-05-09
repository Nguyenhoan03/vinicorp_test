function openDeleteModal(itemId, deleteUrl) {
    if (confirm(`Bạn có chắc chắn muốn xóa?`)) {
        $.ajax({
            url: deleteUrl,
            type: "DELETE",
            data: {
                id: itemId,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                alert('Xóa thành công!');
                location.reload();
            },
            error: function(xhr) {
                alert('Có lỗi xảy ra. Vui lòng thử lại.');
            }
        });
    }
}
