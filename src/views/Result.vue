<template>
	<div class="guess">
		<div class="headSpace"></div>
		<v-row justify="center">
			<div class="double">
				<h2>{{ user.displayName }}さん<br />宛のほめ言葉</h2>
			</div>
		</v-row>
		<v-row justify="center">
			<v-col cols="5" v-for="(message, index) in message" :key="index">
				<v-card width="200" :color="colorList[colorIndex[index]]">
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

		<v-row justify="center" class="my-8">
			<v-btn
				outlined
				color="teal lighten-3 mb-6"
				class="font-weight-bold"
				@click="goHome()"
			>
				<v-icon dark left>mdi-home </v-icon>
				Homeへ
			</v-btn>
		</v-row>
	</div>
</template>

<script>
// @ is an alias to /src
import axios from "axios";
// import { off } from 'process';
export default {
	name: "Guess",
	data() {
		return {
			wWidth: window.innerWidth, //ウィンドウサイズ
			wHeight: window.innerWidth,
			member: [],
			memberID: [],
			memberPhotoURL: [],
			memberName: [],
			message: [],
			roomID: null,
			user: this.$store.state.user,
			colorList: [
				"red lighten-3",
				"light-blue lighten-4",
				"green lighten-4",
				"purple lighten-4",
				"yellow lighten-4",
				"orange lighten-3",
				"brown lighten-4",
				"blue-grey lighten-4",
			],
			colorIndex: [],
			select: [],
			uid: this.$store.state.user.uid,
		};
	},
	async created() {
		await this.getUserInfo();
		await this.getToMeMessage();
		await this.selectColor();
		console.log("colorIndex", this.colorIndex);
		// this.pickupMessage()
		//console.log("uid", this.uid)
	},
	methods: {
		goHome() {
			this.$router.push("/");
		},
		async getToMeMessage() {
			// メッセージの情報とってくる
			return axios
				.get(
					`https://ryota.nkmr.io/m1_hackathon_2022/get_room_id_praise.php?room_id=${this.$store.state.roomID}&to_id=${this.uid}`,
					{}
				)
				.then((res) => {
					console.log("こう", res.data);
					this.message = res.data;
					console.log(this.message);
					// return res.data
				})
				.catch((error) => {
					console.log("err:", error);
				});
		},
		async getUserInfo() {
			console.log(this.$store.state.member);
			console.log(this.$store.state.memberPhoto);
			console.log(this.$store.state.memberName);
			this.member = this.$store.state.member;
			this.memberPhotoURL = this.$store.state.memberPhoto;
			this.memberName = this.$store.state.memberName;
			this.memberID = this.$store.state.memberID;
		},
		async selectColor() {
			for (let i = 0; i < this.message.length; i++) {
				for (let j = 0; j < this.memberID.length; j++) {
					if (this.message[i].from_id == this.memberID[j]) {
						this.colorIndex.push(j);
					}
				}
			}
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
.headSpace {
	height: 5rem;
}
.double {
	width: 100%;
	text-align: center;
}
.double h2 {
	position: relative;
	display: inline-block;
	color: #4d2a01c5;
	width: 200px;
}
.double h2:before,
.double h2:after {
	content: "";
	display: block;
	width: 20%;
	height: 4px;
	border-top: 1px solid #4d2a01c5;
	border-bottom: 1px solid #4d2a01c5;
	position: absolute;
	top: 50%;
	/* margin-top:-2px; */
}
.double h2:before {
	left: -25%;
}
.double h2:after {
	right: -25%;
}
</style>
