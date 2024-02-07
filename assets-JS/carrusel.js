document.addEventListener("DOMContentLoaded", function () {
    //Definir las imágenes y la información que se mostrarán en el carrusel.
    const peliculas = [
        {
            titulo: "Barbie",
            clasificacion: "B",
            director: "Greta Gerwig",
            productor: "Tom Ackerley, Robbie Brenner y David Heyman",
            actores: ["Margot Robbie", "Ryan Gosling"],
            imagen: "/CineStreaming/assets-imagines/barbie.jpg",
        },
        {
            titulo: "Fragmentado",
            clasificacion: "PG-13",
            director: "M. Night Shyamalan",
            productor: "Marc Bienstock, M. Night Shyamalan y Jason Blum",
            actores: ["James McAvoy", "Anya Taylor-Joy"],
            imagen: "/CineStreaming/assets-imagines/fragmentado.jpg",
        },
        {
            titulo: "Avatar",
            clasificacion: "PG-13",
            director: "James Cameron",
            productor: "James Cameron, Jon Landau y Rae Sanchini",
            actores: ["Sam Worthington", "Zoe Saldaña"],
            imagen: "/CineStreaming/assets-imagines/avatar.jpg",
        },
        {
            titulo: "Pokémon: la película",
            clasificacion: "A",
            director: "Kunihiko Yuyama",
            productor: "Choji Yoshikawa, Tomoyuki Igarashi y Takemoto Mori",
            actores: ["Rika Matsumoto", "Mayumi Iizuka"],
            imagen: "/CineStreaming/assets-imagines/pokemon.jpg",
        },
        {
            titulo: "Scott Pilgrim vs. the World ",
            clasificacion: "A",
            director: "Edgar Wright",
            productor: "Marc Platt, Edgar Wright y Eric Gitter",
            actores: ["Michael Cera", "Mary Elizabeth Winstead"],
            imagen: "/CineStreaming/assets-imagines/scott.jpg",
        },

    ];

    let indicePelicula = 0;

    //Función para cambiar la película en el carrusel.
    function cambiarPelicula() {
        const carrusel = document.getElementById("carrusel");
        const infoPelicula = document.getElementById("infoPelicula");

        //Obtener la película actual.
        const peliculaActual = peliculas[indicePelicula];

        //Mostrar la imagen y la información de la película.
        carrusel.src = peliculaActual.imagen;
        infoPelicula.textContent = `${peliculaActual.titulo} – Clasificación: ${peliculaActual.clasificacion}`;
    }

    //Función para avanzar manualmente al hacer clic en el botón siguiente.
    function siguientePelicula() {
        indicePelicula = (indicePelicula + 1) % peliculas.length;
        cambiarPelicula();
    }

    //Función para cambiar el tamaño de la imagen al doble de tamaño.
    function redimensionarImagen() {
        const carrusel = document.getElementById("carrusel");

        //Obtener el tamaño actual de la imagen.
        const anchoActual = carrusel.width;
        const altoActual = carrusel.height;

        //Redimensionar la imagen al doble de tamaño.
        carrusel.style.width = `${anchoActual * 2}px`;
        carrusel.style.height = `${altoActual * 2}px`;
    }

    //Función para restaurar el tamaño original de la imagen al quitar el cursor.
    function restaurarTamañoOriginal() {
        const carrusel = document.getElementById("carrusel");

        //Restaurar el tamaño original de la imagen.
        carrusel.style.width = "";
        carrusel.style.height = "";
    }

    //Función para mostrar información de la película al hacer clic en la imagen.
    function mostrarInformacionPelicula() {
        const carrusel = document.getElementById("carrusel");
        const infoPelicula = document.getElementById("infoPelicula");

        //Obtener la película actual.
        const peliculaActual = peliculas[indicePelicula];

        //Mostrar la información de la película.
        infoPelicula.textContent = `Director: ${peliculaActual.director}, Productor: ${peliculaActual.productor}, Actores: ${peliculaActual.actores.join(", ")}`;
    }

    //Evento OnLoad: Iniciar el carrusel al cargar la página.
    window.onload = function () {
        // Llamar a la función cambiarPelicula cada 8 segundos.
        setInterval(cambiarPelicula, 8000);

        //Obtener el botón siguiente y agregar un evento de clic.
        const siguienteBtn = document.getElementById("siguienteBtn");
        siguienteBtn.addEventListener("click", siguientePelicula);

        //Obtener la imagen del carrusel y agregar eventos de mouseover, mouseout y clic.
        const carrusel = document.getElementById("carrusel");
        carrusel.addEventListener("mouseover", redimensionarImagen);
        carrusel.addEventListener("mouseout", restaurarTamañoOriginal);
        carrusel.addEventListener("click", mostrarInformacionPelicula);
    };
});
