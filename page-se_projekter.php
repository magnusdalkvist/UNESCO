<?php
/**
 * The template for displaying front page
 */

get_header();
?>

<template>
	<article class="grid-menu">
      	<img src="" alt="" />
      	<div class="info">
			<h3 class="title"></h3>
			<p class="desc"></p>
      </div>
    </article>
</template>

<style>
	#content {
		background: #dee5c8;
	}

	h2::before {
		display: none;
	}

	#container {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
      gap: 1rem;
    }

    article {
      cursor: pointer;
	  background: white;
	  display: flex;
	  flex-direction: column;
	  border-radius: 10px;
    }

    .grid-menu img {
      width: 100%;
	  height: 100%;
      border-radius: 10px 10px 0 0;
      object-fit: cover;
      aspect-ratio: 8/5;
    }

    .info {
      padding: 1rem;
	  background: grey;
	  border-radius: 0 0 10px 10px;
    }

	.info h3, .info p {
		color: white;
	}

	.globalt-medborgerskab {
		background: #C2202F;
	}

	.baeredygtig-udvikling {
		background: #4AA047;
	}

	.unesco-verdensmalsskoler {
		background: #186B9D;
	}

	p.desc {
		margin: 0;
	}

	#filter {
		display: flex;
		flex-wrap: wrap;
		flex-direction: row;
		gap: 1rem;
		margin: 1rem 0;
	}
	
	.ast-container {
		max-width: 1500px;
	}

	.selected, .selected:hover, .selected:focus {
		background: #315743;
		color: white;
	}

	@media (max-width: 921px) {
	  #container {
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      }
	}

</style>

	<div id="primary" class="content-area">
		<main id="main" class="site-main"></main><!-- #main -->
		<nav id="filter">
			<button data-projekt="alle" class="selected">Alle</button>
			<button data-projekt="5">Bæredygtig udvikling</button>
			<button data-projekt="6">Globalt medborgerskab</button>
			<button data-projekt="24">Unesco Verdensmålsskoler</button>
			<select>
				<option value="alle">Verdensmål</option>
				<option value="7">1 - Afskaf fattigdom</option>
				<option value="8">2 - Stop sult</option>
				<option value="9">3 - Sundhed og trivsel</option>
				<option value="10">4 - Kvalitetsuddannelse</option>
				<option value="11">5 - Ligestilling mellem kønnene</option>
				<option value="12">6 - Rent vand og sanitet</option>
				<option value="13">7 - Bæredygtig energi</option>
				<option value="14">8 - Anstændige jobs og økonomisk vækst</option>
				<option value="15">9 - Industri innovation og infrastruktur</option>
				<option value="16">10 - Mindre ulighed</option>
				<option value="17">11 - Bæredygtige byer og lokalsamfund</option>
				<option value="18">12 - Ansvarligt forbrug og produktion</option>
				<option value="19">13 - Klimaindsats</option>
				<option value="20">14 - Livet i havet</option>
				<option value="21">15 - Livet på land</option>
				<option value="22">16 - Fred retfærdighed og stærke institutioner</option>
				<option value="23">17 - Partnerskaber for handling</option>
			</select>
		</nav>
		<section id="container"></section>
		
		<script>
			let projekter;
			let categories;
			let filterProjekt = "alle";
			const select = document.querySelector("#filter select");

			const dbUrl = "https://tessafan.dk/kea/09_CMS/UNESCO-wp/wp-json/wp/v2/projekt?per_page=100";
			const catUrl = "https://tessafan.dk/kea/09_CMS/UNESCO-wp/wp-json/wp/v2/categories?per_page=100";

			async function getJson() {
				const data = await fetch(dbUrl);
				const catdata = await fetch(catUrl);
				projekter = await data.json();
				categories = await catdata.json();
				console.log(projekter);
				console.log(categories);
				visProjekter();
				addEventListenerToButtons()
				addEventListenerToSelector()
			}

			function addEventListenerToButtons() {
				document.querySelectorAll("#filter button").forEach(element => {
					element.addEventListener("click", filtrering)
				})
			}

			function filtrering() {
				filterProjekt = this.dataset.projekt;
				document.querySelector(".selected").classList.remove("selected");
     			this.classList.add("selected");
				document.querySelector("select").value = "alle";
				visProjekter();
				console.log(filterProjekt);
			}

			function addEventListenerToSelector() {
				select.addEventListener("click", filtreringSelect)
			}

			function filtreringSelect() {
				filterProjekt = select.options[select.selectedIndex].value;
				document.querySelector(".selected").classList.remove("selected");
				document.querySelector("#filter button:first-of-type").classList.add("selected");
				visProjekter();
			}

			function visProjekter() {
				let container = document.querySelector("#container");
     			let temp = document.querySelector("template");
				container.innerHTML = ""; 
				projekter.forEach(projekt => {
					if (filterProjekt == "alle" || projekt.categories.includes(parseInt(filterProjekt))) {
						console.log(projekt.categories);
						let klon = temp.cloneNode(true).content;
						klon.querySelector("img").src = projekt.image.guid;
						klon.querySelector(".title").textContent = projekt.title.rendered;
						if (projekt.categories.includes(6)) {
							klon.querySelector(".info").classList.add("globalt-medborgerskab");
						} else if (projekt.categories.includes(5)) {
							klon.querySelector(".info").classList.add("baeredygtig-udvikling");
						} else if (projekt.categories.includes(24)) {
							klon.querySelector(".info").classList.add("unesco-verdensmalsskoler");
						}
						
						klon.querySelector(".desc").textContent = projekt.beskrivelse;
						klon.querySelector("article").addEventListener("click", () => {
							location.href = projekt.link;
						})
						container.appendChild(klon);
					}
				})
			}
			getJson();
			


		</script>
	</div><!-- #primary -->

<?php
get_footer();
