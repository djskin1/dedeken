document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.navbar-nav li.menu-item-has-children > a')
    .forEach(function(link) {
      link.addEventListener('click', function(e) {
        // voorkomt dat direct naar link wordt gegaan
        e.preventDefault();  
        const parent = this.parentElement;
        parent.classList.toggle('open');
      });
    });
});
