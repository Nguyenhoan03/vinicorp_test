
function toggleAddEquimentForm() {
    const form = document.getElementById('addDeviceForm');
    form.classList.toggle('hidden');
}

window.onload = () => {
    const alertBox = document.querySelector('[role="alert"]');
    if (alertBox) {
        setTimeout(() => alertBox.remove(), 4000);
    }
};
function toggleEditForm() {
    const form = document.getElementById('editDeviceForm');
    form.classList.toggle('hidden');
}

function showEditForm(button) {
    const id = button.getAttribute('data-id');
    const name = button.getAttribute('data-name');
    const type = button.getAttribute('data-type');
    const status = button.getAttribute('data-status');

    toggleEditForm();

    const form = document.getElementById('editForm');
    form.action = `/assets/edit?id=${id}`;

    document.getElementById('edit_name').value = name;
    document.getElementById('edit_type').value = type;
    document.getElementById('edit_status').value = status;
}

function openDeleteRoleModal(button) {
    const id = button.getAttribute('data-id');

    if (confirm('Bạn có chắc chắn muốn xóa không?')) {
        $.ajax({
            url: `{{route('asset.delete')}}/${id}`,
            type: "DELETE",
            data: {
                id: id,
                _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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