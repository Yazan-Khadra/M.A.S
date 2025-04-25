const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
        if(entry.isIntersecting) {
            entry.target.classList.add('show');
        }
        else {
            entry.target.classList.remove('show');
        }
    });
});

const hiddenElements = document.querySelectorAll('.hidden');
const hiddenYElements = document.querySelectorAll('.hidden-Y');
hiddenElements.forEach((e1) => observer.observe(e1));
hiddenYElements.forEach((e2) => observer.observe(e2));