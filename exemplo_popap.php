<script type="text/javascript" src="/_libes_tme/jquery.1.12.0.js" charset="utf-8"></script>	 


<style>
:root {
  --lightgray: #efefef;
  --blue: steelblue;
  --white: #fff;
  --black: rgba(0, 0, 0, 0.8);
  --bounceEasing: cubic-bezier(0.51, 0.92, 0.24, 1.15);
}

* {
  padding: 0;
  margin: 0;
}

a {
  color: inherit;
  text-decoration: none;
}

button {
  cursor: pointer;
  background: transparent;
  border: none;
  outline: none;
  font-size: inherit;
}


.btn-group {
  text-align: center;
}

.open-modal {
  font-weight: bold;
  background: var(--blue);
  color: var(--white);
  padding: 0.75rem 1.75rem;
  margin-bottom: 1rem;
  border-radius: 5px;
}


/* MODAL
–––––––––––––––––––––––––––––––––––––––––––––––––– */
.modal {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 1rem;
  background: var(--black);
  cursor: pointer;
  visibility: hidden;
  opacity: 0;
  transition: all 0.35s ease-in;
}

.modal.is-visible {
  visibility: visible;
  opacity: 1;
}

.modal-dialog {
  position: relative;
  max-width: 800px;
  max-height: 80vh;
  border-radius: 5px;
  background: var(--white);
  overflow: auto;
  cursor: default;
}

.modal-dialog > * {
  padding: 1rem;
}

.modal-header,
.modal-footer {
  background: var(--lightgray);
}

.modal-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.modal-header .close-modal {
  font-size: 1.5rem;
}

.modal p + p {
  margin-top: 1rem;
}


/* FOOTER
–––––––––––––––––––––––––––––––––––––––––––––––––– */
.page-footer {
  position: absolute;
  bottom: 1rem;
  right: 1rem;
}

.page-footer span {
  color: #e31b23;
}
</style>	
<script>
const openEls = document.querySelectorAll("[data-open]");
const closeEls = document.querySelectorAll("[data-close]");
const isVisible = "is-visible";

for (const el of openEls) {
  el.addEventListener("click", function() {
    const modalId = this.dataset.open;
    document.getElementById(modalId).classList.add(isVisible);
  });
}

for (const el of closeEls) {
  el.addEventListener("click", function() {
    this.parentElement.parentElement.parentElement.classList.remove(isVisible);
  });
}

document.addEventListener("click", e => {
  if (e.target == document.querySelector(".modal.is-visible")) {
    document.querySelector(".modal.is-visible").classList.remove(isVisible);
  }
});

document.addEventListener("keyup", e => {
  // if we press the ESC
  if (e.key == "Escape" && document.querySelector(".modal.is-visible")) {
    document.querySelector(".modal.is-visible").classList.remove(isVisible);
  }
});	
	
</script>
	
	
	
	
<div class="btn-group">
  <button type="button" class="open-modal" data-open="modal1">
    Launch first modal
  </button>
  <button type="button" class="open-modal" data-open="modal2">
    Launch second modal
  </button>
</div>

<div class="modal" id="modal1">
  <div class="modal-dialog">
    <header class="modal-header">
      The header of the first modal
      <button class="close-modal" aria-label="close modal" data-close>
        ✕  
      </button>
    </header>
    <section class="modal-content">
      <p><strong>Press ✕, ESC, or click outside of the modal to close it</strong></p>
      <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quo repellendus reprehenderit accusamus totam ratione! Nesciunt, nemo dolorum recusandae ad ex nam similique dolorem ab perspiciatis qui. Facere, dignissimos. Nemo, ea.</p>
      <p>Nor again is there anyone who loves or pursues or desires to obtain pain of itself, because it is pain, but because occasionally circumstances occur in which toil and pain can procure him some great pleasure. To take a trivial example, which of us ever undertakes laborious physical exercise, except to obtain some advantage from it? But who has any right to find fault with a man who chooses to enjoy a pleasure that has no annoying consequences, or one who avoids a pain that produces no resultant pleasure</p>
      <p>Nullam vitae enim vel diam elementum tincidunt a eget metus. Curabitur finibus vestibulum rutrum. Vestibulum semper tellus vitae tortor condimentum porta. Sed id ex arcu. Vestibulum eleifend tortor non purus porta dapibus</p>
      <p>Nor again is there anyone who loves or pursues or desires to obtain pain of itself, because it is pain, but because occasionally circumstances occur in which toil and pain can procure him some great pleasure. To take a trivial example, which of us ever undertakes laborious physical exercise, except to obtain some advantage from it? But who has any right to find fault with a man who chooses to enjoy a pleasure that has no annoying consequences, or one who avoids a pain that produces no resultant pleasure</p>
    </section>
    <footer class="modal-footer">
      The footer of the first modal
    </footer>
  </div>
</div>

<div class="modal" id="modal2">
  <div class="modal-dialog">
    <header class="modal-header">
      The header of the second modal
      <button class="close-modal" aria-label="close modal" data-close>
        ✕  
      </button>
    </header>
    <section class="modal-content">
      <p><strong>Press ✕, ESC, or click outside of the modal to close it</strong></p>
      <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quo repellendus reprehenderit accusamus totam ratione! Nesciunt, nemo dolorum recusandae ad ex nam similique dolorem ab perspiciatis qui. Facere, dignissimos. Nemo, ea.</p>
      <p>Nullam vitae enim vel diam elementum tincidunt a eget metus. Curabitur finibus vestibulum rutrum. Vestibulum semper tellus vitae tortor condimentum porta. Sed id ex arcu. Vestibulum eleifend tortor non purus porta dapibus</p>
    </section>
    <footer class="modal-footer">
      The footer of the second modal
    </footer>
  </div>
</div>

<footer class="page-footer">
  <small>Made with <span>❤</span> by <a href="http://georgemartsoukos.com/" target="_blank">George Martsoukos</a>
  </small>
</footer>	


















<style>
:root {
  --lightgray: #efefef;
  --blue: steelblue;
  --white: #fff;
  --black: rgba(0, 0, 0, 0.8);
  --bounceEasing: cubic-bezier(0.51, 0.92, 0.24, 1.15);
}

* {
  padding: 0;
  margin: 0;
}

a {
  color: inherit;
  text-decoration: none;
}

button {
  cursor: pointer;
  background: transparent;
  border: none;
  outline: none;
  font-size: inherit;
}

body {
  display: flex;
  align-items: center;
  justify-content: center;
  height: 100vh;
  font: 16px/1.5 sans-serif;
}

.btn-group {
  text-align: center;
}

.open-modal {
  font-weight: bold;
  background: var(--blue);
  color: var(--white);
  padding: 0.75rem 1.75rem;
  margin-bottom: 1rem;
  border-radius: 5px;
}


/* MODAL
–––––––––––––––––––––––––––––––––––––––––––––––––– */
.modal {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 1rem;
  background: var(--black);
  cursor: pointer;
  visibility: hidden;
  opacity: 0;
  transition: all 0.35s ease-in;
}

.modal.is-visible {
  visibility: visible;
  opacity: 1;
}

.modal-dialog {
  position: relative;
  max-width: 800px;
  max-height: 80vh;
  border-radius: 5px;
  background: var(--white);
  overflow: auto;
  cursor: default;
}

.modal-dialog > * {
  padding: 1rem;
}

.modal-header,
.modal-footer {
  background: var(--lightgray);
}

.modal-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.modal-header .close-modal {
  font-size: 1.5rem;
}

.modal p + p {
  margin-top: 1rem;
}


/* SLIDE LEFT ANIMATION
–––––––––––––––––––––––––––––––––––––––––––––––––– */
[data-animation="slideInOutLeft"] .modal-dialog {
  opacity: 0;
  transform: translateX(-100%);
  transition: all 0.5s var(--bounceEasing);
}

[data-animation="slideInOutLeft"].is-visible .modal-dialog {
  opacity: 1;
  transform: none;
  transition-delay: 0.2s;
}


/* FOOTER
–––––––––––––––––––––––––––––––––––––––––––––––––– */
.page-footer {
  position: absolute;
  bottom: 1rem;
  right: 1rem;
}

.page-footer span {
  color: #e31b23;
}

</style>

<script>
const openEls = document.querySelectorAll("[data-open]");
const closeEls = document.querySelectorAll("[data-close]");
const isVisible = "is-visible";

for (const el of openEls) {
  el.addEventListener("click", function() {
    const modalId = this.dataset.open;
    document.getElementById(modalId).classList.add(isVisible);
  });
}

for (const el of closeEls) {
  el.addEventListener("click", function() {
    this.parentElement.parentElement.parentElement.classList.remove(isVisible);
  });
}

document.addEventListener("click", e => {
  if (e.target == document.querySelector(".modal.is-visible")) {
    document.querySelector(".modal.is-visible [data-close]").click();
  }
});

document.addEventListener("keyup", e => {
  // if we press the ESC
  if (e.key == "Escape" && document.querySelector(".modal.is-visible")) {
    document.querySelector(".modal.is-visible [data-close]").click();
  }
});

</script>

<div class="btn-group">
  <button type="button" class="open-modal" data-open="modal1">
    Launch first modal with a slide animation
  </button>
  <button type="button" class="open-modal" data-open="modal2">
    Launch second modal with the default fade animation
  </button>
</div>

<div class="modal" id="modal1" data-animation="slideInOutLeft">
  <div class="modal-dialog">
    <header class="modal-header">
      The header of the first modal
      <button class="close-modal" aria-label="close modal" data-close>
        ✕  
      </button>
    </header>
    <section class="modal-content">
      <p><strong>Press ✕, ESC, or click outside of the modal to close it</strong></p>
      <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quo repellendus reprehenderit accusamus totam ratione! Nesciunt, nemo dolorum recusandae ad ex nam similique dolorem ab perspiciatis qui. Facere, dignissimos. Nemo, ea.</p>
      <p>Nor again is there anyone who loves or pursues or desires to obtain pain of itself, because it is pain, but because occasionally circumstances occur in which toil and pain can procure him some great pleasure. To take a trivial example, which of us ever undertakes laborious physical exercise, except to obtain some advantage from it? But who has any right to find fault with a man who chooses to enjoy a pleasure that has no annoying consequences, or one who avoids a pain that produces no resultant pleasure</p>
      <p>Nullam vitae enim vel diam elementum tincidunt a eget metus. Curabitur finibus vestibulum rutrum. Vestibulum semper tellus vitae tortor condimentum porta. Sed id ex arcu. Vestibulum eleifend tortor non purus porta dapibus</p>
      <p>Nor again is there anyone who loves or pursues or desires to obtain pain of itself, because it is pain, but because occasionally circumstances occur in which toil and pain can procure him some great pleasure. To take a trivial example, which of us ever undertakes laborious physical exercise, except to obtain some advantage from it? But who has any right to find fault with a man who chooses to enjoy a pleasure that has no annoying consequences, or one who avoids a pain that produces no resultant pleasure</p>
    </section>
    <footer class="modal-footer">
      The footer of the first modal
    </footer>
  </div>
</div>

<div class="modal" id="modal2">
  <div class="modal-dialog">
    <header class="modal-header">
      The header of the second modal
      <button class="close-modal" aria-label="close modal" data-close>
        ✕  
      </button>
    </header>
    <section class="modal-content">
      <p><strong>Press ✕, ESC, or click outside of the modal to close it</strong></p>
      <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quo repellendus reprehenderit accusamus totam ratione! Nesciunt, nemo dolorum recusandae ad ex nam similique dolorem ab perspiciatis qui. Facere, dignissimos. Nemo, ea.</p>
      <p>Nullam vitae enim vel diam elementum tincidunt a eget metus. Curabitur finibus vestibulum rutrum. Vestibulum semper tellus vitae tortor condimentum porta. Sed id ex arcu. Vestibulum eleifend tortor non purus porta dapibus</p>
    </section>
    <footer class="modal-footer">
      The footer of the second modal
    </footer>
  </div>
</div>

<footer class="page-footer">
  <small>Made with <span>❤</span> by <a href="http://georgemartsoukos.com/" target="_blank">George Martsoukos</a>
  </small>
</footer>














<style>
:root {
  --lightgray: #efefef;
  --blue: steelblue;
  --white: #fff;
  --black: rgba(0, 0, 0, 0.8);
  --bounceEasing: cubic-bezier(0.51, 0.92, 0.24, 1.15);
}

* {
  padding: 0;
  margin: 0;
}

a {
  color: inherit;
  text-decoration: none;
}

button {
  cursor: pointer;
  background: transparent;
  border: none;
  outline: none;
  font-size: inherit;
}

body {
  display: flex;
  align-items: center;
  justify-content: center;
  height: 100vh;
  font: 16px/1.5 sans-serif;
}

.btn-group {
  text-align: center;
}

.open-modal {
  font-weight: bold;
  background: var(--blue);
  color: var(--white);
  padding: 0.75rem 1.75rem;
  margin-bottom: 1rem;
  border-radius: 5px;
}


/* MODAL
–––––––––––––––––––––––––––––––––––––––––––––––––– */
.modal {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 1rem;
  background: var(--black);
  cursor: pointer;
  visibility: hidden;
  opacity: 0;
  transition: all 0.35s ease-in;
}

.modal.is-visible {
  visibility: visible;
  opacity: 1;
}

.modal-dialog {
  position: relative;
  max-width: 800px;
  max-height: 80vh;
  border-radius: 5px;
  background: var(--white);
  overflow: auto;
  cursor: default;
}

.modal-dialog > * {
  padding: 1rem;
}

.modal-header,
.modal-footer {
  background: var(--lightgray);
}

.modal-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.modal-header .close-modal {
  font-size: 1.5rem;
}

.modal p + p {
  margin-top: 1rem;
}


/* ANIMATIONS
–––––––––––––––––––––––––––––––––––––––––––––––––– */
[data-animation] .modal-dialog {
  opacity: 0;
  transition: all 0.5s var(--bounceEasing);
}

[data-animation].is-visible .modal-dialog {
  opacity: 1;
  transition-delay: 0.2s;
}

[data-animation="slideInOutDown"] .modal-dialog {
  transform: translateY(100%);
}

[data-animation="slideInOutTop"] .modal-dialog {
  transform: translateY(-100%);
}

[data-animation="slideInOutLeft"] .modal-dialog {
  transform: translateX(-100%);
}

[data-animation="slideInOutRight"] .modal-dialog {
  transform: translateX(100%);
}

[data-animation="zoomInOut"] .modal-dialog {
  transform: scale(0.2);
}

[data-animation="rotateInOutDown"] .modal-dialog {
  transform-origin: top left;
  transform: rotate(-1turn);
}

[data-animation="mixInAnimations"].is-visible .modal-dialog {
  animation: mixInAnimations 2s 0.2s linear forwards;
}

[data-animation="slideInOutDown"].is-visible .modal-dialog,
[data-animation="slideInOutTop"].is-visible .modal-dialog,
[data-animation="slideInOutLeft"].is-visible .modal-dialog,
[data-animation="slideInOutRight"].is-visible .modal-dialog,
[data-animation="zoomInOut"].is-visible .modal-dialog,
[data-animation="rotateInOutDown"].is-visible .modal-dialog {
  transform: none;
}

@keyframes mixInAnimations {
  0% {
    transform: translateX(-100%);
  }

  10% {
    transform: translateX(0);
  }

  20% {
    transform: rotate(20deg);
  }

  30% {
    transform: rotate(-20deg);
  }

  40% {
    transform: rotate(15deg);
  }

  50% {
    transform: rotate(-15deg);
  }

  60% {
    transform: rotate(10deg);
  }

  70% {
    transform: rotate(-10deg);
  }

  80% {
    transform: rotate(5deg);
  }

  90% {
    transform: rotate(-5deg);
  }

  100% {
    transform: rotate(0deg);
  }
}


/* FOOTER
–––––––––––––––––––––––––––––––––––––––––––––––––– */
.page-footer {
  position: absolute;
  bottom: 1rem;
  right: 1rem;
}

.page-footer span {
  color: #e31b23;
}
</style>
<script>
const openEls = document.querySelectorAll("[data-open]");
const closeEls = document.querySelectorAll("[data-close]");
const isVisible = "is-visible";

for (const el of openEls) {
  el.addEventListener("click", function() {
    const modalId = this.dataset.open;
    document.getElementById(modalId).classList.add(isVisible);
  });
}

for (const el of closeEls) {
  el.addEventListener("click", function() {
    this.parentElement.parentElement.parentElement.classList.remove(isVisible);
  });
}

document.addEventListener("click", e => {
  if (e.target == document.querySelector(".modal.is-visible")) {
    document.querySelector(".modal.is-visible").classList.remove(isVisible);
  }
});

document.addEventListener("keyup", e => {
  // if we press the ESC
  if (e.key == "Escape" && document.querySelector(".modal.is-visible")) {
    document.querySelector(".modal.is-visible").classList.remove(isVisible);
  }
});
</script>

<div class="btn-group">
  <button type="button" class="open-modal" data-open="modal1">
    Launch first modal with a slide animation
  </button>
  <button type="button" class="open-modal" data-open="modal2">
    Launch second modal with a bunch of animations
  </button>
</div>

<div class="modal" id="modal1" data-animation="slideInOutLeft">
  <div class="modal-dialog">
    <header class="modal-header">
      The header of the first modal
      <button class="close-modal" aria-label="close modal" data-close>
        ✕  
      </button>
    </header>
    <section class="modal-content">
      <p><strong>Press ✕, ESC, or click outside of the modal to close it</strong></p>
      <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quo repellendus reprehenderit accusamus totam ratione! Nesciunt, nemo dolorum recusandae ad ex nam similique dolorem ab perspiciatis qui. Facere, dignissimos. Nemo, ea.</p>
      <p>Nor again is there anyone who loves or pursues or desires to obtain pain of itself, because it is pain, but because occasionally circumstances occur in which toil and pain can procure him some great pleasure. To take a trivial example, which of us ever undertakes laborious physical exercise, except to obtain some advantage from it? But who has any right to find fault with a man who chooses to enjoy a pleasure that has no annoying consequences, or one who avoids a pain that produces no resultant pleasure</p>
      <p>Nullam vitae enim vel diam elementum tincidunt a eget metus. Curabitur finibus vestibulum rutrum. Vestibulum semper tellus vitae tortor condimentum porta. Sed id ex arcu. Vestibulum eleifend tortor non purus porta dapibus</p>
      <p>Nor again is there anyone who loves or pursues or desires to obtain pain of itself, because it is pain, but because occasionally circumstances occur in which toil and pain can procure him some great pleasure. To take a trivial example, which of us ever undertakes laborious physical exercise, except to obtain some advantage from it? But who has any right to find fault with a man who chooses to enjoy a pleasure that has no annoying consequences, or one who avoids a pain that produces no resultant pleasure</p>
    </section>
    <footer class="modal-footer">
      The footer of the first modal
    </footer>
  </div>
</div>

<div class="modal" id="modal2" data-animation="mixInAnimations">
  <div class="modal-dialog">
    <header class="modal-header">
      The header of the second modal
      <button class="close-modal" aria-label="close modal" data-close>
        ✕  
      </button>
    </header>
    <section class="modal-content">
      <p><strong>Press ✕, ESC, or click outside of the modal to close it</strong></p>
      <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quo repellendus reprehenderit accusamus totam ratione! Nesciunt, nemo dolorum recusandae ad ex nam similique dolorem ab perspiciatis qui. Facere, dignissimos. Nemo, ea.</p>
      <p>Nullam vitae enim vel diam elementum tincidunt a eget metus. Curabitur finibus vestibulum rutrum. Vestibulum semper tellus vitae tortor condimentum porta. Sed id ex arcu. Vestibulum eleifend tortor non purus porta dapibus</p>
    </section>
    <footer class="modal-footer">
      The footer of the second modal
    </footer>
  </div>
</div>

<footer class="page-footer">
  <small>Made with <span>❤</span> by <a href="http://georgemartsoukos.com/" target="_blank">George Martsoukos</a>
  </small>
</footer>