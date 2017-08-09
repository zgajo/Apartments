<template>
	<form id="form" class="box">
	<div>
		<footer style="margin-bottom: 2em;"><img src="/img/photomania-kuca.jpg" class="imgVelicina">
		</footer>
		<div class="group">
			<input v-model="user" id="user" type="email" @keyup="ocistiKriviPodaci"  @keyup.enter="chkUser"><span class="highlight"></span><span class="bar"></span>
			<label>Email</label>
		</div>
		<div class="group">
			<input v-model="pass" id="pass" type="password" @keyup="ocistiKriviPodaci"  @keyup.enter="chkUser"><span class="highlight"></span><span class="bar"></span>
			<label>Lozinka</label>
		</div>
		<div style="display: none; color: red; margin-bottom: 20px; text-align: center; margin-top: -20px" id="kriviPodaci">Upisani krivi podaci</div>
		<div style="display: none; color: red; margin-bottom: 20px; text-align: center; margin-top: -20px" id="kriviEmail">Potrebno je upisati email adresu</div>
		<button type="button" v-on:click="chkUser" class="button buttonBlue">Prijavi se
			<div class="ripples buttonRipples"><span class="ripplesCircle"></span></div>
		</button>
		<div class="overlay" id="spinner" style="display:none">
        <i class="fa fa-refresh fa-spin"></i>
      </div>
      </div>
      <div id="loadSpinner" style="text-align: center; display:none; margin-top: -300px;">
    		<v-progress-circular indeterminate v-bind:size="50" class="primary--text"></v-progress-circular>
		</div>
	</form>
</template>

<script>

	export default {

		name: 'Login',

		data () {
			return {
				user: '',
				pass: ''
			};
		},
		methods: {
			ocistiKriviPodaci(){
				$("#kriviPodaci").hide();
				$("#kriviEmail").hide();
			},
			chkUser(){
				var user = this.user;
				var pass = this.pass;
				var router = this.$router;
				var store = this.$store;
				var auth = this.$auth;

				if(!user.includes("@")) {$("#kriviEmail").show(); return;}

					if(user =="" || pass == ""){
						if(user ==="" && pass === "") {
							$('#pass').css('border', '2px solid red');
							$('#user').css('border', '2px solid red');
						}
						else if(user === "") {
							$('#pass').removeAttr('style');
							$('#user').css('border', '2px solid red');
						}
						else {
							$('#user').removeAttr('style');
							$('#pass').css('border', '2px solid red');
						}
					}
				// OBA POLJA UPISANA, PROVJERA ISPRAVNOSTI PODATAKA
				else{

				 	axios.post('/user/authentificate', {
				 		email: user,
				 		password: pass
				 	})
				 	.then(function(response) {

	 					auth.setToken(response.data.token);
	 					localStorage.setItem('permissions', response.data.permissions);
	 					localStorage.setItem('user', response.data.username);

	 					$("#loadSpinner").show();

				 			setTimeout(() => {
				 				$("#loadSpinner").hide();
					 			router.push('/')
					 		},1000)

				 	})
				 	.catch(function(response){
				 		console.log('error: '+response)
				 		$('#user').removeAttr('style');
					 	$('#pass').removeAttr('style');
					 	$("#kriviPodaci").show();
				 	})
				}

			}
		}
	};


	$(window, document, undefined).ready(function() {

		$('input').blur(function() {
			var $this = $(this);
			if ($this.val())
				$this.addClass('used');
			else
				$this.removeClass('used');
		});

		var $ripples = $('.ripples');

		$ripples.on('click.Ripples', function(e) {

			var $this = $(this);
			var $offset = $this.parent().offset();
			var $circle = $this.find('.ripplesCircle');

			var x = e.pageX - $offset.left;
			var y = e.pageY - $offset.top;

			$circle.css({
				top: y + 'px',
				left: x + 'px'
			});

			$this.addClass('is-active');

		});

		$ripples.on('animationend webkitAnimationEnd mozAnimationEnd oanimationend MSAnimationEnd', function(e) {
			$(this).removeClass('is-active');
		});

	});









</script>

<style scoped>

	hgroup { 
		text-align:center;
		margin-top: 4em;
	}

	h1, h3 { font-weight: 300; }

	h1 { color: #636363; }

	h3 { color: #4a89dc; }

	form {
		width: 330px; 
		height: 600px;
		/*margin: 4em auto;*/
		padding: 3em 2em 2em 2em;
		background: #fafafa;
		border: 1px solid #ebebeb;
		box-shadow: rgba(0,0,0,0.14902) 0px 1px 1px 0px,rgba(0,0,0,0.09804) 0px 1px 2px 0px;
		top: 50%;
	    left: 50%;
	    margin-left: -165px;
	    position: fixed;
	    margin-top: -300px;
	}

	.imgVelicina{
		width: 15em;
	}

	.group { 
		position: relative; 
		margin-bottom: 45px; 
	}

	input {
		font-size: 18px;
		padding: 10px 10px 10px 5px;
		-webkit-appearance: none;
		display: block;
		background: #fafafa;
		color: #636363;
		width: 100%;
		border: none;
		border-radius: 0;
		border-bottom: 1px solid #757575;
	}

	input:focus { outline: none; }


	/* Label */

	label {
		color: #999; 
		font-size: 18px;
		font-weight: normal;
		position: absolute;
		pointer-events: none;
		left: 5px;
		top: 10px;
		-webkit-transition:all 0.2s ease;
		transition: all 0.2s ease;
	}


	/* active */

	input:focus ~ label, input.used ~ label {
		top: -20px;
		-webkit-transform: scale(.75);
		transform: scale(.75); left: -2px;
		/* font-size: 14px; */
		color: #4a89dc;
	}


	/* Underline */

	.bar {
		position: relative;
		display: block;
		width: 100%;
	}

	.bar:before, .bar:after {
		content: '';
		height: 2px; 
		width: 0;
		bottom: 1px; 
		position: absolute;
		background: #4a89dc; 
		-webkit-transition:all 0.2s ease; 
		transition: all 0.2s ease;
	}

	.bar:before { left: 50%; }

	.bar:after { right: 50%; }


	/* active */

	input:focus ~ .bar:before, input:focus ~ .bar:after { width: 50%; }


	/* Highlight */

	.highlight {
		position: absolute;
		height: 60%; 
		width: 100px; 
		top: 25%; 
		left: 0;
		pointer-events: none;
		opacity: 0.5;
	}


	/* active */

	input:focus ~ .highlight {
		-webkit-animation: inputHighlighter 0.3s ease;
		animation: inputHighlighter 0.3s ease;
	}


	/* Animations */

	@-webkit-keyframes inputHighlighter {
		from { background: #4a89dc; }
		to 	{ width: 0; background: transparent; }
	}

	@keyframes inputHighlighter {
		from { background: #4a89dc; }
		to 	{ width: 0; background: transparent; }
	}


	/* Button */
/*
	.button {
		position: relative;
		display: inline-block;
		padding: 12px 24px;
		margin: .3em 0 1em 0;
		width: 100%;
		vertical-align: middle;
		color: #fff;
		font-size: 16px;
		line-height: 20px;
		-webkit-font-smoothing: antialiased;
		text-align: center;
		letter-spacing: 1px;
		background: transparent;
		border: 0;
		border-bottom: 2px solid #E0435C;
		cursor: pointer;
		-webkit-transition:all 0.15s ease;
		transition: all 0.15s ease;
	}
	.button:focus { outline: 0; }



	.buttonBlue {
		background: #E0435C;
	}

	.buttonBlue:hover { background: #803539; }
*/


.button {
  position: relative;
  display: inline-block;
  padding: 12px 24px;
  margin: .3em 0 1em 0;
  width: 100%;
  vertical-align: middle;
  color: #fff;
  font-size: 16px;
  line-height: 20px;
  -webkit-font-smoothing: antialiased;
  text-align: center;
  letter-spacing: 1px;
  background: transparent;
  border: 0;
  border-bottom: 2px solid #3160B6;
  cursor: pointer;
  -webkit-transition:all 0.15s ease;
  transition: all 0.15s ease;
}
.button:focus { outline: 0; }




.buttonBlue {
  background: #4a89dc;
  text-shadow: 1px 1px 0 rgba(39, 110, 204, .5);
}

.buttonBlue:hover { background: #357bd8; }







/* Ripples container */

.ripples {
	position: absolute;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	overflow: hidden;
	background: transparent;
}


/* Ripples circle */

.ripplesCircle {
	position: absolute;
	top: 50%;
	left: 50%;
	-webkit-transform: translate(-50%, -50%);
	transform: translate(-50%, -50%);
	opacity: 0;
	width: 0;
	height: 0;
	border-radius: 50%;
	background: rgba(255, 255, 255, 0.25);
}

.ripples.is-active .ripplesCircle {
	-webkit-animation: ripples .4s ease-in;
	animation: ripples .4s ease-in;
}


/* Ripples animation */

@-webkit-keyframes ripples {
	0% { opacity: 0; }

	25% { opacity: 1; }

	100% {
		width: 200%;
		padding-bottom: 200%;
		opacity: 0;
	}
}

@keyframes ripples {
	0% { opacity: 0; }

	25% { opacity: 1; }

	100% {
		width: 200%;
		padding-bottom: 200%;
		opacity: 0;
	}
}

footer { text-align: center; clear:both; }

footer p {
	color: #888;
	font-size: 13px;
	letter-spacing: .4px;
}

footer a {
	color: #4a89dc;
	text-decoration: none;
	-webkit-transition:all .2s ease;
	transition: all .2s ease;
}

footer a:hover {
	color: #666;
	text-decoration: underline;
}

footer img {
	width: 80px;
	-webkit-transition:all .2s ease;
	transition: all .2s ease;
}

footer img:hover { opacity: .83; }

footer img:focus , footer a:focus { outline: none; }


@media (max-width: 480px) {
	form {
		width: 300px !important;
		margin: 0em auto;
		height: 460px ;
		padding: 1em 1em 1em 1em;
		top: 50%;
	    left: 50%;
	    margin-left: -150px;
	    position: fixed;
	    margin-top: -230px;
	}
	.imgVelicina{
		width:10em;
	}
}
</style>