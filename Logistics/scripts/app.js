//declaring constants to be used
const showAddMovie = document.getElementById('add-modal');
const backDrop = document.getElementById('backdrop');
const addMovieButton = document.getElementById('btn')
const cancelAddMovieButton = showAddMovie.querySelector('.btn--passive')
const saveMovieButton = cancelAddMovieButton.nextElementSibling;
const userInputs = showAddMovie.querySelectorAll('input');
const movies = [];
const homeText = document.getElementById('entry-text');
const listRoot = document.getElementById('movie-list');
// functions


// update the UI when a movie is added
const updateUi = () => {
    if(movies.length === 0 ){

    } else 
    {
        homeText.style.display = 'none';
    }
}   
// Populate the movie list on the UI

const updateMovieList = (title, rating) => {
    const newMovieElement = document.createElement('li');
   newMovieElement.className = 'movie-element';
   newMovieElement.innerHTML = `
   <div class="movie-element__image">
      
   </div>
   <div class =  "movie-element___info">
       <h2>${title}</h2>
       <p>${rating}/5 stars</p>
   </div>
   `
   listRoot.append(newMovieElement);
}

//toggle the backdrop
const toggleBackdropHandler = () => {
    backDrop.classList.toggle('visible')
}

//toggle the modal class to add movies
const addMovieHandler = ()=> {
    showAddMovie.classList.toggle('visible')
    toggleBackdropHandler();
    clearInputs();
}
 
// Clear Inputs
const clearInputs = ()=> {
    for (const usriput of userInputs){
        usriput.value = '';
    }
}

//save a movie

const saveMovieHandler = () => {
    const title = userInputs[0].value;
    const imageUrl = userInputs[1].value;
    const rating = userInputs[2].value;

    if(title.trim() === '' || imageUrl.trim() === '' 
    || rating.trim() === '' || rating < 0 || rating > 5){
            alert('Invalid Input Moron!');
            return;
    }

    const newMovie = {
        movieTitle :title,
        movieImageUrl :imageUrl,
        movieRating :rating,
    };
 movies.push(newMovie);
 console.log(movies);
 addMovieHandler();
 clearInputs();
 updateMovieList(title, rating);
 updateUi();

}



//linking buttons to functions
addMovieButton.addEventListener('click', addMovieHandler);
backDrop.addEventListener('click',addMovieHandler);
cancelAddMovieButton.addEventListener('click',addMovieHandler);

saveMovieButton.addEventListener('click', saveMovieHandler);


