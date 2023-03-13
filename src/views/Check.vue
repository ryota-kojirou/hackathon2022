<template>
	<div class="check">
		<div class="headSpace"></div>
		<!-- <v-container fluid>
			<v-row class="text-center" v-if="memberPhotoURL.length > 0">
				<v-col cols="6" v-for="index in 4" :key="index">
					<v-card :color="colorList[nowShowing.to_index[index - 1]]" >
						<v-avatar class="mt-4">
							<v-img :src="nowShowing.to_photo[index-1]" contain></v-img>
						</v-avatar>
						<v-card-text class="font-weight-bold">
							{{ nowShowing.to_name[index-1] }}
						</v-card-text>
					</v-card>
				</v-col>
			</v-row>
		</v-container> -->

		<v-row justify="center">
			<h2>結果発表</h2>
		</v-row>

		<v-row justify="center">
			<v-avatar> <v-img :src="memberOb.photo[nowPerson]" contain ></v-img> </v-avatar>
			<p class="ma-3 text-h5 font-weight-bold">
				{{ memberOb.name[nowPerson] }}
			</p>

			<p class="my-3 text-h5 font-weight-bold">
				が書いた褒め言葉
			</p>
		</v-row>

		<v-container fluid>
			<v-row class="text-center" v-if="memberPhotoURL.length > 0">
				<v-col cols="6" v-for="index in 4" :key="index">
					<v-avatar :size=wWidth/10 v-for="index2 in memberCount"
					:key="index2" :class="statusList[guessIndex[index-1][index2-1]]" class="ma-1">
						<v-img
							:src="memberPhotoURL[index2 - 1]"
							contain
							@click="test(index2,index)"
						></v-img>
					</v-avatar>
					<v-card :color="colorList[nowShowing.to_index[index - 1]]">
						<v-card-text class="text-h5 font-weight-bold">
							<v-avatar size="30">
							<v-img
								:src="nowShowing.to_photo[index-1]"
								max-width="30"
								height="30"
							></v-img>
						</v-avatar>
							{{ nowShowing.words_of_praise[index-1] }}
						</v-card-text>
					</v-card>
				</v-col>
			</v-row>
		</v-container>

		<v-row justify="center">
			<v-btn
			class="ma-2 font-weight-bold"
			outlined
			color="teal lighten-3"
			:disabled="nowPerson==0"
			@click="toForward()">
			<v-icon
			dark
			left
			>
			mdi-arrow-left
        </v-icon>前へ</v-btn>
				<v-btn
				class="ma-2 font-weight-bold"
			outlined
			color="teal lighten-3"
				:disabled="nowPerson==memberCount-1"
				@click="toNext()"
				>
				次へ
		<v-icon
				dark
				right
				>
				mdi-arrow-right
        </v-icon></v-btn>
			</v-row>

			<v-row justify="center" class="my-3">
			<v-btn
			class="ma-2 font-weight-bold"
			outlined
			color="teal lighten-3"
			v-if="nowPerson==memberCount-1"
			@click="goResult()"
				><p class="my-3 text-h5 font-weight-bold">
					自分に集まったほめ言葉をみる
				</p></v-btn>
		</v-row>
	</div>
</template>

<script>
// import { fromBits } from "long";
import axios from "axios";
// @ is an alias to /src
export default {
	name: "Check",
	components: {},
	data() {
		return {
			wWidth: window.innerWidth, //ウィンドウサイズ
			wHeight: window.innerWidth,

			memberCount: 0,
			memberPhotoURL: [],
			GuessMemberPhotoURL: [],
			memberName: ["a", "o", "k", "i"],
			memberID: [],
			message: ["かわいい", "すき", "偉い", "嫌い"],
			randomMessage: [],
			roomID: this.$store.state.roomID,
			colorList: [
				"red lighten-3",
				"light-blue lighten-4",
				"green lighten-4",
				"purple lighten-4",
				"yellow lighten-4",
				"orange lighten-3",
				"brown lighten-1",
				"blue-grey lighten-4",
			],
			select: [],
			check: false,
			toggle_exclusive: [],
			praise: {
				from_id: [],
				photoURL: [],
				words_of_praise: [],
			},
			guessMessage: 0,
			nowShowing: {
				praise_id: [],
				from_id: [],
				to_id: [],
				to_index: [],
				to_photo: [],
				to_name: [],
				words_of_praise: [],
				guess_id:[],
			},
			nowPhoto: [],
			nowName: [],
			nowPerson: 0,
			memberOb: {
				id: [],
				name: [],
				photo: [],
			},
			nowGuess: [],
			status: [
				["red1", "a", "a", "a", "a", "a", "a", "a"],
				["a", "a", "a", "a", "a", "a", "a", "a"],
				["a", "a", "a", "a", "a", "a", "a", "a"],
				["a", "a", "a", "a", "a", "a", "a", "a"],
				["a", "a", "a", "a", "a", "a", "a", "a"],
			],
			statusList: [
				"red1",
				"blue1",
				"green1",
				"purple1",
				"yellow1",
				"orange1",
				"brown1",
				"grey1",
			],
			count: 0,
			guessIndex:[],
		};
	},
	async created() {
		await this.memberObject();
		await this.getMessage();
		await this.switchFromMessage();
		const temp = await this.getTipAndGuess("94");
		console.log("prase_id入れたらtipとguessだよ", temp);
		// console.log("チップスターフォトだよ",this.tipsterPhoto("94"));
		// this.tipsterPhoto(94);


		console.log("roomID:", this.$store.state.roomID);

		this.memberCount = this.$store.state.memberName.length;
		console.log("memberCountは", this.memberCount)

		// for (let i = 0; i < this.$store.state.memberPhoto.length; i++) {
		// 	this.memberPhotoURL.push(this.$store.state.memberPhoto[i]);
		// }
		// console.log("this.memberPhotoURLは", this.memberPhotoURL);
		this.$forceUpdate()

		console.log("this.nowShowingの中身", this.nowShowing);
	},
	mounted() {},
	methods: {
		test(a, b) {
			console.log(a, b)
			console.log(this.status.index);
		},

		async getMessage() {
			// メッセージの情報とってくる
			return axios
				.get(
					`https://ryota.nkmr.io/m1_hackathon_2022/get_praise_room_id.php?room_id=${this.roomID}`,
					{}
				)
				.then((res) => {
					// console.log(res.data);
					this.$store.commit("setMessage", { message: res.data });
					// for (var i = 0; i < res.data.length; i++) {
					// 	//return await res.data[i].words_of_praise
					// 	this.praise.words_of_praise.push(res.data[i].words_of_praise);
					// 	this.praise.from_id.push(res.data[i].from_id);
					// }
					console.log("message:", this.$store.state.message);
					// return res.data
				})
				.catch((error) => {
					console.log("err:", error);
				});
		},
		memberObject() {
			// メンバー情報はmemberに全部まとめる
			for (let i = 0; i < this.$store.state.member.length; i++) {
				this.memberOb.id.push(this.$store.state.member[i].user_id);
				this.memberOb.name.push(this.$store.state.memberName[i]);
				this.memberOb.photo.push(this.$store.state.memberPhoto[i]);
			}
			console.log(this.memberOb);
		},
		toNext(){
			if(this.nowPerson>1 || this.nowPerson<this.memberCount){
				this.nowPerson++;
				this.switchFromMessage();
				console.log("toNextでnowPersonは",this.nowPerson)
			}
		},
		toForward(){
			if(this.nowPerson>2 || this.nowPerson<this.memberCount+1){
				this.nowPerson--;
				this.switchFromMessage();
				console.log("toForwardでnowPersonは",this.nowPerson)
			}
		},
		goResult(){
			if(this.$store.state.host==true){
				fetch(`https://ryota.nkmr.io/m1_hackathon_2022/insert_room_table.php?room_id=${this.roomID}&host_id=${this.$store.state.user.uid}&user_id=${this.$store.state.user.uid}&status=close`)
			}
			
			this.$router.push(`/result/${this.$store.state.roomID}`);
		},
		async switchFromMessage() {
			// 提示するメッセージの書き手チェンジ
			// 配列全部初期化
			this.nowShowing.praise_id = [];
			this.nowShowing.from_id = [];
			this.nowShowing.to_id = [];
			this.nowShowing.to_index = [];
			this.nowShowing.to_photo = [];
			this.nowShowing.to_name = [];
			this.nowShowing.guess_id = [];
			this.nowShowing.words_of_praise = [];

			this.nowName = [];
			this.nowPhoto = [];

			// for (let i = 0; i < this.status.length; i++) {
			// 	for (let j = 0; j < this.status.length; j++) {
			// 		this.status[i][j] = "red1";
			// 	}
			// }
			console.log("ステップ1");

			// console.log("メッセージ", this.$store.state.message);
			// console.log("メンバー", this.memberOb);

			// いま提示する書き手が書いたメッセージをとってくる
			for (let i = 0; i < this.$store.state.message.length; i++) {
				// console.log(this.memberOb.id[this.nowPerson],"と",this.$store.state.message[i].from_id);
				//いま提示する書き手とメッセージの書き手が一致した時nowShowwingにpushする
				// console.log("ステップ2");
				if (this.memberOb.id[this.nowPerson] ==this.$store.state.message[i].from_id) {
					// console.log("言葉",this.$store.state.message[i].words_of_praise);
					this.nowShowing.words_of_praise.push(
						this.$store.state.message[i].words_of_praise
					);
					console.log("ステップ3");
					// console.log("言葉のid",this.$store.state.message[i].praise_id);
					this.nowShowing.praise_id.push(this.$store.state.message[i].praise_id);
					// console.log("誰へ",this.$store.state.message[i].to_id);
					this.nowShowing.to_id.push(this.$store.state.message[i].to_id);
					const to_index = this.memberOb.id.findIndex(id => id === this.$store.state.message[i].to_id)



					this.nowShowing.to_index.push(to_index)
					this.nowShowing.to_name.push(this.memberOb.name[to_index])
					this.nowShowing.to_photo.push(this.memberOb.photo[to_index])

					this.nowShowing.from_id.push(this.$store.state.message[i].from_id);
					console.log("ステップ4");

					console.log("praiseID送る", this.$store.state.message[i].praise_id);

					// await this.getGuess(this.$store.state.message[i].praise_id);
					console.log("ステップ5");

					console.log("お願いします", this.$store.state.guess);

					this.memberPhotoURL = await this.tipsterPhoto(this.$store.state.message[i].praise_id);
					console.log(this.memberPhotoURL);



					for (let j = 0; j < this.$store.state.guess.length; j++) {
						this.count = 0;
						for (let p = 0; p < this.$store.state.memberName.length; p++) {
							if (this.$store.state.guess[j].guess_id == this.memberOb.id[p]) {
								console.log("とおた？");
								console.log(i,this.count,"の色が", this.statusList[p]);
								// this.status[i][this.count+1] = this.statusList[p];
								this.count = this.count + 1;
							}
						}
					}
					// for (let p = 0; p < this.$store.state.memberName.length; p++) {
					// 	for (let p = 0; p < this.$store.state.memberName.length; p++) {
					// 		if(this.memberOb.id[p])
					// 	}
					// }
					console.log("ステップ6");
				}
			}

			let guessID = [];
			this.guessIndex = [];
			// let guessIndex = [];
			for (let i = 0; i < this.nowShowing.praise_id.length; i++) {
				let b = [];
				b.push(await this.getGuess_id(this.nowShowing.praise_id[i]));
				guessID.push(b);
			}
			console.log("guessID入ったかな", guessID[1][0].length);

			for (let i = 0; i < guessID.length; i++) {
				let b = [];
				for (let j = 0; j < guessID[i][0].length; j++) {
					const guess_index = this.memberOb.id.findIndex(id => id === guessID[i][0][j])
					b.push(guess_index);
				}
				this.guessIndex.push(b);
			}
			console.log("guessIndex入ったかな",this.guessIndex);


			console.log(this.nowShowing)

			//次の人にいく
			// this.nowPerson = this.nowPerson + 1;
			// if (this.nowPerson == this.memberCount) {
			// 	this.$router.push(`/result/${this.$store.state.roomID}`);
			// }
		},
		async tipsterPhoto(praiseID) {
			let photoURL = [];
			let tipster = [];
			let guess_id=[];
			const tipsterAndGuesses = await this.getTipAndGuess(praiseID)
			for (let n = 0; n < tipsterAndGuesses.length; n++) {
				tipster.push(tipsterAndGuesses[n].tipster_id);
				//guess_idここでとるかも
				guess_id.push(tipsterAndGuesses[n].guess_id);
				// this.nowShowing.guess_id.push(tipsterAndGuesses[n].guess_id);
			}
			// console.log(this.$store.state.memberName)
			for (let k = 0; k < tipster.length; k++) {
				for (let n = 0; n < this.$store.state.memberName.length; n++) {
					if (tipster[k] == this.memberOb.id[n]) {
						photoURL.push(this.memberOb.photo[n]);
					}
				}
			}
			return photoURL
		},
		async getGuess_id(praiseID) {
			let guess_id=[];
			const tipsterAndGuesses = await this.getTipAndGuess(praiseID)
			for (let n = 0; n < tipsterAndGuesses.length; n++) {
				guess_id.push(tipsterAndGuesses[n].guess_id);
				// this.nowShowing.guess_id.push(tipsterAndGuesses[n].guess_id);
			}
			// console.log(this.$store.state.memberName)
			return guess_id
		},
		// getGuessColor() {

		// },
		// praiseIDを入れたらtipster_idとguess＿idがオブジェクトが入った配列で返ってくる
		getTipAndGuess(praiseID) {
			return axios
					.get(
						`https://ryota.nkmr.io/m1_hackathon_2022/get_tipster.php?praise_id=${praiseID}`, {})
					.then((res) => {
						return res.data
					})
					.catch((error) => {
						console.log("err:", error);
						return error;
					});
		},
		// async getGuess(praiseID) {
		// 	// みんなの予想結果とってくる
		// 	return axios
		// 		.get(
		// 			`https://ryota.nkmr.io/m1_hackathon_2022/get_tipster.php?praise_id=${praiseID}`,
		// 			{}
		// 		)
		// 		.then((res) => {
		// 			console.log(praiseID);
		// 			this.$store.commit("setGuess", { guess: res.data });

		// 		})
		// 		.catch((error) => {
		// 			console.log("err:", error);
		// 			return error;
		// 		});
		// // },
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
p {
	color: #00000099;
	font-family: "M PLUS 1p";
}
h1{
	color: #00000099;
}
h2{
	color: #4d2a01c5;
    text-align:center;
}
h2:before,h2:after {
    content:"";
    display:inline-block;
    width:30px;
    height:1px;
    margin:0 10px;
    background-color: rgb(229, 188, 153);
    vertical-align: middle;
}
.headSpace{
	height: 5rem;
}
/* .guessIcon {
	outline: 4px solid red;
	outline-offset: 0px;
} */
/* .v-avatar {
	outline: 4px solid red;
	outline-offset: -4px;
} */
.red1 {
	outline: 4px solid #F44336;
	outline-offset: 0px;
}
.blue1 {
	outline: 4px solid #03A9F4;
	outline-offset: 0px;
}
.green1 {
	outline: 4px solid #4CAF50;
	outline-offset: 0px;
}
.purple1 {
	outline: 4px solid #9C27B0;
	outline-offset: 0px;
}
.yellow1 {
	outline: 4px solid #FFEB3B;
	outline-offset: 0px;
}
.orange1 {
	outline: 4px solid #FF9800;
	outline-offset: 0px;
}

.brown1 {
	outline: 4px solid #795548;
	outline-offset: 0px;
}
.grey1 {
	outline: 4px solid #607D8B;
	outline-offset: 0px;
}
</style>
