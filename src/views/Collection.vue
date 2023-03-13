<template>
	<div class="guess">
		<div class="headSpace"></div>
		<div class="double">
			<h2>過去にもらった<br>ほめ言葉</h2>
		</div>
		<br />
		<v-row justify="center">
			<v-col cols="5" v-for="(message, index) in message" :key="index">
				<v-card width="200" color="amber lighten-5">
					<v-card-title>
						<v-avatar>
							<v-img
								:src="message.photoURL"
								contain
								max-width="40"
								height="40"
							></v-img>
						</v-avatar>
					</v-card-title>
					<v-card-text>
						<div>{{ message.words_of_praise }}</div>
					</v-card-text>
				</v-card>
			</v-col>
		</v-row>
		<br />
		<br />
		<v-row justify="center">
			<v-btn 
			color="brown lighten-5"
			@click="goHome()">
				<v-icon
          dark
          left
        >mdi-home
        </v-icon>
		Homeへ戻る</v-btn>
		</v-row>
		<br />
		<br />
	</div>
</template>

<script>
// @ is an alias to /src
import axios from "axios";
export default {
	name: "Guess",
	data() {
		return {
			wWidth: window.innerWidth,
			wHeight: window.innerWidth,
			member: [],
			memberPhotoURL: [],
			memberName: [],
			message: [],
			roomID: null,
			colorList: [
				"red lighten-4",
				"light-blue lighten-4",
				"green lighten-4",
				"purple lighten-4",
				"yellow lighten-4",
				"deep-orange lighten-4",
				"brown lighten-4",
			],
			select: [],
			uid: this.$store.state.user.uid,
		};
	},
	created() {
		this.pickupMessage();
		//console.log("uid", this.uid)
	},
	methods: {
		goHome() {
			this.$router.push("/");
		},
		pickupMessage() {
			//過去褒められた言葉を持ってくる
			axios
				.get(
					`https://ryota.nkmr.io/m1_hackathon_2022/get_praise.php?to_id=${this.uid}`,
					{}
				)
				.then((res) => {
					console.log(res.data);
					console.log(this.uid);
					this.message = res.data;
				});
		},
		selectColor() {
			console.log(this.select);
		},
		handleResize: function() {
			// ウインドウサイズが変わるたびにサイズを代入
			this.wWidth = window.innerWidth;
			this.wHeight = window.innerHeight;
			if (this.wWidth > this.wHeight) {
				this.wHeight = window.innerWidth;
			}
		},
	},
};
</script>

<style scoped>
.headSpace{
	height: 5rem;
}
.double{
	width:100%;
	text-align:center;
}
.double h2{
	position:relative;
	display:inline-block;
    color: #4d2a01c5;
    width: 200px;
}
.double h2:before, .double h2:after{
	content:"";
	display:block;
	width:20%;
	height:4px;
	border-top:1px solid #4d2a01c5;;
	border-bottom:1px solid #4d2a01c5;;
	position:absolute;
	top:50%;
	/* margin-top:-2px; */
}
.double h2:before{left:-25%;}
.double h2:after{right:-25%;}
</style>
