let contador = 0;

function mostrarModal() {
  const modal = document.getElementById("modal-ejercicio");
  modal.style.display = "block";
  modal.classList.add("show");
}

function cerrarModal() {
  const modal = document.getElementById("modal-ejercicio");
  modal.style.display = "none";
  modal.classList.remove("show");
}

function guardarEjercicio() {
  contador++;
  const nombre = document.getElementById("nombre").value;
  const series = document.getElementById("series").value;
  const repeticiones = document.getElementById("repeticiones").value;
  const peso = document.getElementById("peso").value;
  const unidad = document.getElementById("unidad").value;

  if (!nombre || !series || !repeticiones || !peso || !unidad) {
    alert("Por favor, completa todos los campos.");
    return;
  }

  const contenedor = document.getElementById("ejercicios");

  const div = document.createElement("div");
  div.className = "ejercicio";
  div.innerHTML = `
    <h4>Ejercicio ${contador}</h4>
    <label>Nombre:</label>
    <input type="text" name="ejercicio[${contador}][nombre]" value="${nombre}" required>

    <label>Series:</label>
    <input type="number" name="ejercicio[${contador}][series]" value="${series}" required>

    <label>Repeticiones:</label>
    <input type="number" name="ejercicio[${contador}][repeticiones]" value="${repeticiones}" required>

    <label>Peso:</label>
    <input type="number" name="ejercicio[${contador}][peso]" value="${peso}" required>

    <label>Unidad:</label>
    <select name="ejercicio[${contador}][unidad]" required>
      <option value="lb" ${unidad === "lb" ? "selected" : ""}>lb</option>
      <option value="kg" ${unidad === "kg" ? "selected" : ""}>kg</option>
    </select>

    <button type="button" class="eliminar-btn" onclick="eliminarEjercicio(this)">Eliminar</button>
    <hr>
  `;

  contenedor.appendChild(div);
  cerrarModal();
  limpiarModal();
}

function eliminarEjercicio(boton) {
  const ejercicio = boton.parentElement;
  ejercicio.remove();
}

function limpiarModal() {
  document.getElementById("nombre").value = "";
  document.getElementById("series").value = "";
  document.getElementById("repeticiones").value = "";
  document.getElementById("peso").value = "";
  document.getElementById("unidad").value = "lb";
}

function obtenerNombreDia(fechaStr) {
  const dias = ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"];
  const [anio, mes, dia] = fechaStr.split("-");
  const fecha = new Date(anio, mes - 1, dia);
  return dias[fecha.getDay()];
}

document.addEventListener("DOMContentLoaded", function () {
  const fechaInput = document.querySelector('input[name="fecha"]');
  const diaInput = document.getElementById("dia");

  fechaInput.addEventListener("change", function () {
    const dia = obtenerNombreDia(fechaInput.value);
    diaInput.value = dia;
  });
  cerrarModal();
});

function enviarRutina() {
  const fecha = document.querySelector('input[name="fecha"]').value;
  const dia = document.getElementById("dia").value;
  const grupo = document.querySelector('input[name="grupo"]').value;

  if (!fecha || !dia || !grupo) {
    alert("Por favor completa todos los campos principales.");
    return;
  }

  const ejercicios = [];
  const contenedores = document.querySelectorAll(".ejercicio");

  contenedores.forEach((div) => {
    const nombre = div.querySelector('input[name*="[nombre]"]').value;
    const series = div.querySelector('input[name*="[series]"]').value;
    const repeticiones = div.querySelector('input[name*="[repeticiones]"]').value;
    const peso = div.querySelector('input[name*="[peso]"]').value;
    const unidad = div.querySelector('select[name*="[unidad]"]').value;

    ejercicios.push({ nombre, series, repeticiones, peso, unidad });
  });

  fetch("procesar.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify({
      fecha,
      dia,
      grupo,
      ejercicios
    })
  })
    .then(response => response.text())
    .then(data => {
      document.body.innerHTML = data;
    })
    .catch(error => {
      console.error("Error:", error);
    });
}
