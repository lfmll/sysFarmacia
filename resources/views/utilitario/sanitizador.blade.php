<script>
document.addEventListener('DOMContentLoaded', function () {
    const inputs = document.querySelectorAll('input[type="text"], input[type="search"], textarea');
    const excepcionesMayusculas = ['token', 'correo', 'razon_social','sistema'];

    inputs.forEach(input => {
        input.addEventListener('blur', function () {
            let valor = input.value.trim(); // siempre se limpia
            const id = input.id;
            const name = input.name;

            if (!excepcionesMayusculas.includes(id) && !excepcionesMayusculas.includes(name)) {
                valor = valor.toUpperCase();
            }

            input.value = valor;
        });
    });
});
</script>
