function back_to_dashbaord() {
    Swal.fire({
        title: "Redirigiendo...",
        text: "Serás enviado al panel principal.",
        icon: "info",
        timer: 3000,
        showConfirmButton: false,
        willClose: () => {
            window.location.href = "../index.php";
        }
    });
}
