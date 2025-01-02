var TEXTS = [
  "Authentic Italian cuisine at its finest. Each dish is a taste of Italy.<br><br>5/5<br>Italy Magazine",
  "The ambiance, food, and cocktails are top-notch.<br>A hidden gem!<br><br>5/5<br>New York Newspaper",
  "A must stop in the Toronto area! The pizzas are remarkable - straight from Italy!<br><br>9/10<br>World Cuisine",
  "What they've brought to Toronto is outstanding.<br><br>&#9733&#9733&#9733&#9733<br>The Newspaper",
  "An exquisite dining experience. Italy should be proud of the food that's served.<br><br>&#9733&#9733&#9733&#9733&#9733<br>Toronto Newspaper",
];

var indexText = 0;

function changeText() {
  var reviewText = document.getElementById('review-text-change');
  reviewText.style.opacity = 0;
  setTimeout(function() {
    reviewText.innerHTML = TEXTS[indexText++];
    reviewText.style.opacity = 1;
    if (indexText === TEXTS.length) indexText = 0;
  }, 3000);
}

setInterval(changeText, 7500);

// var IMAGES = [
//   {
//     src: "/LittleItaly/images/pizza/pizza5.jpg",
//     alt: "Two pizzas with selective focus",
//     unsplash: "https://unsplash.com/photos/selective-focus-photography-of-two-pizzas-exSEmuA7R7k"
//   },
//   {
//     src: "/LittleItaly clear glasses with colorful juices",
//     unsplash: "https://unsplash.com/photos/three-clear-glass-cups-with-juice-xBFTjrMIC0c"
//   },
//   {
//     src: "/LittleItaly/images/pasta/pasta2.jpg",
//     alt: "Pasta dish on black ceramic bowl",
//     unsplash: "https://unsplash.com/photos/pasta-dish-on-black-ceramic-bowl-d9jcPTRD9fo"
//   },
//   {
//     src: "/LittleItaly/images/drink/drink4.jpg",
//     alt: "White ceramic cup on brown wooden table",
//     unsplash: "https://unsplash.com/photos/white-ceramic-cup-on-brown-wooden-table-KWZ-rg9o76A"
//   },
//   {
//     src: "/LittleItaly/images/pasta/pasta3.jpg",
//     alt: "Pasta in tomato sauce",
//     unsplash: "https://unsplash.com/photos/pasta-in-tomato-sauce-PLyJqEJVre0"
//   },
//   {
//     src: "/LittleItaly/images/pizza/pizza4.jpg",
//     alt: "Pizza on brown wooden table",
//     unsplash: "https://unsplash.com/photos/pizza-on-brown-wooden-table-40OJLYVWeeM"
//   }
// ];LittleItaly

var IMAGES = [
  "/LittleItaly/images/pizza/pizza5.jpg", // https://unsplash.com/photos/selective-focus-photography-of-two-pizzas-exSEmuA7R7k
  "/LittleItaly/images/drink/drink1.jpg", // https://unsplash.com/photos/three-clear-glass-cups-with-juice-xBFTjrMIC0c
  "/LittleItaly/images/pasta/pasta2.jpg", // https://unsplash.com/photos/pasta-dish-on-black-ceramic-bowl-d9jcPTRD9fo
  "/LittleItaly/images/drink/drink4.jpg", // https://unsplash.com/photos/white-ceramic-cup-on-brown-wooden-table-KWZ-rg9o76A
  "/LittleItaly/images/pasta/pasta3.jpg", // https://unsplash.com/photos/pasta-in-tomato-sauce-PLyJqEJVre0
  "/LittleItaly/images/pizza/pizza4.jpg", // https://unsplash.com/photos/pizza-on-brown-wooden-table-40OJLYVWeeM
];







var indexImage = 0;

function changeImage() {
  var reviewImage = document.getElementById('review-image-change');
  reviewImage.style.opacity = 0;
  setTimeout(function() {
    reviewImage.src = IMAGES[indexImage++];
    reviewImage.style.opacity = 1;
    if (indexImage === IMAGES.length) indexImage = 0;
  }, 3000);
}

setInterval(changeImage, 7500);
