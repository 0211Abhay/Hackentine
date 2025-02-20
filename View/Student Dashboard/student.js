let movies = [
    {
        name: 'How does this even work?" Well, it’s time to find out—and build one yourself!',
        des: 'In collaboration with WnCC IIT Bombay, we’re hosting a hands-on workshop where you’ll not only learn but also create your own chatbot in real time.',
        image: 'images/1.jpg'
    },
    {
        name: 'Get ready to unlock the power of AI in Open Source!',
        des: 'Learn how AI can make your contributions smarter and more impactful with S&T - IIT Kanpur happening today at 8:00 PM!',
        image: 'images/2.jpg'
    },
    {
        name: 'Workshop on Learning and integrating API from scratch with WnCC - IIT Bombay is now LIVE.',
        des: 'This workshop will help you understand API fundamentals and even create your first API in a live coding experience!',
        image: 'images/3.jpg'
    },
    {
        name: 'Orientation Session for 10X Club Core Team Members and Board Members !',
        des: 'Join our Program Head, Mr. Abhishek Agrawal, to discuss the mission, motive, and vision of the 10X Clubs, and how together, we can revolutionize the coding culture in your colleges',
        image: 'images/4.jpg'
    },
    {
        name: 'how AI actually understands language & Why RAG is the backbone of modern AI apps .',
        des: 'AI is getting smarter every day, and tools like Perplexity AI are proof of that! But how do they find the right answers so fast? The secret is Retrieval-Augmented Generation (RAG).',
        image: 'images/5.jpg'
    } ,
    {
        name: 'Anatomy of a Chatbot 2.0',
        des: '- Discover how chatbots are designed and built , Create a chatbot that can have smooth conversations , Learn how chatbots remember past conversations , No experience needed! Our workshop is interactive, easy to follow, and perfect for beginners.',
        image: 'images/6.jpg'
    },
    {
        name: 'build your own AI model from scratch !',
        des: 'Ready to take your AI skills to the next level? In this workshop, you won’t just learn about RAG—you’ll build your own AI model from scratch !',
        image: 'images/7.jpg'
    },
    {
        name: 'Mentorship Session',
        des: 'This is a Mentorship Session.',
        image: 'images/8.jpg'
    }
]

const carousel = document.querySelector('.carousel');
let sliders = [];

let slideIndex = 0; // to track current slide index.

const createSlide = () => {
    if(slideIndex >= movies.length){
        slideIndex = 0;
    }

    // creating DOM element
    let slide = document.createElement('div');
    let imgElement = document.createElement('img');
    let content = document.createElement('div');
    let h1 = document.createElement('h1');
    let p = document.createElement('p');

    
        // attaching all elements
        imgElement.appendChild(document.createTextNode(''));
        h1.appendChild(document.createTextNode(movies[slideIndex].name));
        p.appendChild(document.createTextNode(movies[slideIndex].des));
        content.appendChild(h1);
        content.appendChild(p);
        slide.appendChild(content);
        slide.appendChild(imgElement);
        carousel.appendChild(slide);
    
        // setting up image
    imgElement.src = movies[slideIndex].image;
    slideIndex++;

    // setting elements classname
    slide.className = 'slider';
    content.className = 'slide-content';
    h1.className = 'movie-title';
    p.className = 'movie-des';

    sliders.push(slide);

    if(sliders.length){
        sliders[0].style.marginLeft = `calc(-${100 * (sliders.length - 2)}% - ${30 * (sliders.length - 2)}px)`;
    }
}
for(let i = 0; i < 3; i++){
    createSlide();
}

setInterval(() => {
    createSlide();
}, 3000);

document.addEventListener("DOMContentLoaded", function() {
    let dots = document.querySelectorAll(".dot");
    let index = 0;

    function changeDot() {
        dots.forEach(dot => dot.style.background = "gray");
        dots[index].style.background = "black";
        index = (index + 1) % dots.length;
    }

    setInterval(changeDot, 2000);
});