<template>
	<div>
		<div id="timer">
			<Timer ref="timer" @timeup="isTimeup = true" :timeLimit="60" />
		</div>
		<div class="guess">
			<v-container fluid>
				<v-row class="text-center" justify="center">
					<v-col cols="6" v-for="index in 4" :key="index">
						<v-card :color="colorList[toMembersList[num][index - 1]]">
							<v-card-title class="text-center">
								<v-avatar size="40">
									<v-img
										:src="memberPhotoURL[toMembersList[num][index - 1]]"
										contain
									></v-img>
								</v-avatar>
							</v-card-title>
							<v-card-text>
								{{ memberName[toMembersList[num][index - 1]] }}
							</v-card-text>
						</v-card>
					</v-col>
				</v-row>
			</v-container>
			<div class="predict">
				<v-row justify="center" align-content="center">
					<v-avatar>
						<v-img
							:src="memberPhotoURL[num]"
							contain
							max-width="30"
							height="30"
						></v-img>
					</v-avatar>
					<p class="my-3 text-h5 font-weight-bold">
						{{ memberName[num] }}
					</p>
					<p class="my-3">
						が上記の人たちに向けた褒め言葉
						<!-- </p> -->
						<!-- </v-row> -->
						<!-- <v-row justify="center"> -->
						<!-- <p class="my-3"> -->
						<br />それぞれ誰宛の褒め言葉か予想してみて！
					</p>
				</v-row>
			</div>

			<v-container v-if="me" fluid>
				<v-row class="text-center">
					<v-col cols="6" v-for="index in 4" :key="index">
						<v-card :color="status[index - 1]">
							<v-card-text class="text-h5 font-weight-bold">
								{{ randomMessage[index - 1].words_of_praise }}
								<!-- {{ randomMessage[index-1] }} -->
							</v-card-text>
							<v-card-actions>
								<v-row justify="center">
									<v-btn-toggle
										v-model="toggle_exclusive[index - 1]"
										borderless
										group
										tile
									>
										<v-btn
											v-for="index2 in 4"
											:key="index2"
											class="mx-1"
											fab
											x-small
											@click="
												cardColor(index - 1, toMembersList[num][index2 - 1])
											"
										>
											<v-avatar size="30" class="mx-1" elevation="24">
												<v-img
													:src="memberPhotoURL[toMembersList[num][index2 - 1]]"
													contain
												></v-img>
											</v-avatar>
										</v-btn>
									</v-btn-toggle>
								</v-row>
							</v-card-actions>
						</v-card>
					</v-col>
				</v-row>
			</v-container>
			<v-row justify="center" class="my-6" v-else>
				<p>あなたのほめ言葉をみんなが予想中です<br />少々お待ちください</p>
			</v-row>

			<v-row justify="center">
				<v-btn
					outlined
					color="teal lighten-3 mb-6"
					class="font-weight-bold"
					@click="toNext()"
					:disabled="!isTimeup"
				>
					<v-icon dark left>mdi-chevron-right-circle </v-icon>
					<p class="my-3 text-h5 font-weight-bold">
						{{ nextMessage }}
					</p>
				</v-btn>
			</v-row>
		</div>
	</div>
</template>

<script>
// import { fromBits } from "long";
import axios from "axios";
import Timer from "../components/Timer";

// @ is an alias to /src

export default {
	name: "Guess",

	components: {
		Timer,
	},
	data() {
		return {
			wWidth: window.innerWidth, //ウィンドウサイズ
			wHeight: window.innerWidth,
			member: [],
			memberID: [],
			memberPhotoURL: [],
			memberName: [],
			//message: ["かわいい", "すき", "偉い", "嫌い"],
			message: [],
			randomMessage: [],
			roomID: this.$store.state.roomID,
			toMembersList: this.$store.state.toMembersList,
			num: 0,
			user: this.$store.state.user,
			me: true,
			myIndex: null,
			nextMessage: "次の人の予測へすすむ",
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
			select: [],
			check: false,
			status: ["white", "white", "white", "white"],
			toggle_exclusive: [],
			isTimeup: false,
			isSet: 0,
			//0=>まだ送信してないよ
			//1=>他の人待ち
			//2=>次のフェーズに移っていいよ
		};
	},
	async created() {
		this.getUserInfo();
		this.getMyIndex();
		this.me = this.getMyTurn();
		console.log(this.user.uid);
		//みんなが書いた褒め言葉とってくるよ
		// 提示するメッセージの順番ランダムにする
		this.getMessage().then(() => {
			this.guessMessage();
		});
		this.$setInterval(() => {
			//定期的に実行
			this.checkGuessAll();
		}, 3000);
	},
	mounted() {},
	methods: {
		toNext() {
			if (this.isSet == 0) {
				console.log(this.randomMessage);
				console.log("回数：", this.num);
				console.log("toMembersList", this.toMembersList[this.num]);
				console.log("何回やるの", Object.keys(this.toMembersList).length);

				this.check = true;
				for (var i = 0; i < 4; i++) {
					if (this.toggle_exclusive[i] == null) {
						this.check = false;
						// console.log("selectしてないのはこれ", this.toggle_exclusive[i]);
					}
					for (var j = 0; j < 4; j++) {
						if (
							i != j &&
							this.toggle_exclusive[i] == this.toggle_exclusive[j]
						) {
							this.check = false;
							console.log("被ってるのは", i, "と", j, "番目の選択");
						}
					}
				}
				if (this.me == false) {
					this.check = true;
					//this.isSet = 1;
				}
				if (this.check == false) {
					console.log(this.check);
					alert("selectしてないメッセあるよor予想相手ダブってるよ");
				} else {
					//guessの送信

					for (var n = 0; n < 4; n++) {
						//メンバーID
						console.log(
							this.memberID[
								this.toMembersList[this.num][this.toggle_exclusive[n]]
							]
						);
						//それのメッセージ
						console.log(this.randomMessage[n].praise_id);
						//インサートするよ
						if (this.roomID != "wispt4u3rjexe5iz") {
							fetch(
								`https://ryota.nkmr.io/m1_hackathon_2022/insert_guess_table.php?tipster_id=${
									this.user.uid
								}&guess_id=${
									this.memberID[
										this.toMembersList[this.num][this.toggle_exclusive[n]]
									]
								}&praise_id=${this.randomMessage[n]?.praise_id}`
							);
						}
						//isSetを1にする（他の人待ちの状態）
						//２にするとデバッグモード
						console.log(this.roomID);
						if (this.roomID == "wispt4u3rjexe5iz") {
							this.isSet = 2;
						} else {
							this.isSet = 1;
						}
					}
				}
			}
			//全員の予想が終わったら checkGuessAllから
			if (this.isSet == 2) {
				//次の人に回す
				this.isSet = 0;
				this.num++;
				this.status = ["white", "white", "white", "white"];
				this.isTimeup = false;
				for (var p = 0; p < 4; p++) {
					this.toggle_exclusive[p] = null;
				}
				//getMessageも次の人に回す
				this.getMessage().then(() => {
					this.guessMessage();
				});
				// this.num++;
				this.me = this.getMyTurn();
				this.timerStart();
				if (this.num == Object.keys(this.toMembersList).length - 1) {
					this.nextMessage = "結果へ！";
				} else if (this.num == Object.keys(this.toMembersList).length) {
					this.$clearAllIntervals();
					this.$router.push(`/check/${this.$store.state.roomID}`);
					// this.goCheck()
				}
			}
		},
		getMyIndex() {
			for (var i = 0; i < this.memberID.length; i++) {
				if (this.user.uid == this.memberID[i]) {
					this.myIndex = i;
				}
			}
		},
		getMyTurn() {
			if (this.myIndex == this.num) {
				this.nextMessage = "予想へすすむ";
				this.isSet = 1;
				return false;
			} else {
				this.nextMessage = "次の人の予測へすすむ";
				this.isSet = 0;
				return true;
			}
		},
		timerStart() {
			this.$refs.timer.resetTimer();
		},
		goCheck() {
			// ちゃんと全部誰宛に書かれたものか予想したかみる
			// console.log("selectの結果", this.select);
			this.check = true;
			for (var i = 0; i < 4; i++) {
				if (this.toggle_exclusive[i] == null) {
					this.check = false;
					// console.log("selectしてないのはこれ", this.toggle_exclusive[i]);
				}
				for (var j = 0; j < 4; j++) {
					if (i != j && this.toggle_exclusive[i] == this.toggle_exclusive[j]) {
						this.check = false;
						console.log("被ってるのは", i, "と", j, "番目の選択");
					}
				}
			}
			if (this.check == false) {
				console.log(this.check);
				alert("selectしてないメッセあるよor予想相手ダブってるよ");
			} else if (this.check == true) {
				//ここでみんなそれぞれの予想の結果をデータベースに送りたい
				// データベースに送れたらcheck.vueにいく
				this.$router.push(`/check/${this.$store.state.roomID}`);
			}
		},
		cardColor(card, person) {
			//カードが選択した人の色にするところ
			let oldChoice = this.status[card];
			if (person == 0) {
				this.$set(this.status, card, "red lighten-4");
			} else if (person == 1) {
				this.$set(this.status, card, "light-blue lighten-4");
			} else if (person == 2) {
				this.$set(this.status, card, "green lighten-4");
			} else if (person == 3) {
				this.$set(this.status, card, "purple lighten-4");
			} else if (person == 4) {
				this.$set(this.status, card, "yellow lighten-4");
			} else if (person == 5) {
				this.$set(this.status, card, "orange lighten-4");
			} else if (person == 6) {
				this.$set(this.status, card, "brown lighten-4");
			} else if (person == 7) {
				this.$set(this.status, card, "blue-grey lighten-4");
			}
			if (oldChoice == this.status[card]) {
				this.status[card] = "white";
			}
			console.log(card);
			console.log(person);
			console.log(this.status);
			console.log("選ばれたカード", this.status[card]);
			console.log("toggle_exclusive", this.toggle_exclusive);
		},
		getUserInfo() {
			console.log(this.$store.state.member);
			console.log(this.$store.state.memberPhoto);
			console.log(this.$store.state.memberName);
			this.member = this.$store.state.member;
			this.memberPhotoURL = this.$store.state.memberPhoto;
			this.memberName = this.$store.state.memberName;
			this.memberID = this.$store.state.memberID;
		},
		async getMessage() {
			this.message = [];
			return axios
				.get(
					//roomID軸で持ってくる
					`https://ryota.nkmr.io/m1_hackathon_2022/get_praise_room_id.php?room_id=${this.roomID}`,
					{}
				)
				.then((res) => {
					console.log(res.data);
					console.log(this.num);
					console.log(this.memberID[this.num]);
					for (var i = 0; i < res.data.length; i++) {
						//return await res.data[i].words_of_praise
						console.log(res.data[i].from_id);
						if (res.data[i].from_id == this.memberID[this.num]) {
							//送り主を選定する
							this.message.push(res.data[i]);
							//メッセージの情報入れる
							//toNextで一回一回this.messageの中身からにしている
							//this.message.push(res.data[i].words_of_praise);
						}
					}
					console.log(this.message);
				});
		},
		async checkGuessAll() {
			//Guessを投稿した人数を数える
			console.log(this.message[0].praise_id);
			if (this.isSet == 1) {
				return axios
					.get(
						`https://ryota.nkmr.io/m1_hackathon_2022/get_tipster.php?praise_id=${this.message[0].praise_id}`,
						{}
					)
					.then((res) => {
						console.log(res);
						console.log(res.data.length);
						console.log(this.$store.state.member.length - 1);
						//Guessを投稿した人数と問題になった人以外の人数が一緒だったら
						if (res.data.length == this.$store.state.member.length - 1) {
							//この書き方汚い
							this.isSet = 2;
							this.toNext();
						}
					});
			}
		},
		/*

async getMessage() {
	return axios
	.get(
	`https://ryota.nkmr.io/m1_hackathon_2022/get_praise.php?to_id=w6iLR3S9DiwBuFZXe8XUgjjFRY6J`,
	{}
	)
	.then((res) => {
	console.log(res.data);
	for (var i = 0; i < res.data.length; i++) {
	//return await res.data[i].words_of_praise
	this.message.push(res.data[i].words_of_praise);
	}
	console.log(this.message.length);
	});

},

*/
		async guessMessage() {
			//this.message.push(await this.getMessage());
			//ここで提示する褒め言葉の絞り込み

			// 提示するメッセージの順番ランダムにする
			// console.log(this.message)
			this.randomMessage = this.shuffle(this.message);
			// console.log(this.randomMessage)
		},

		shuffle(array) {
			// 配列の順番ランダムにするための関数
			// console.log(paopao, paopao.length)
			let retArray = array;
			console.log(retArray);
			// for(let i = 0; i<retArray.length; i++){
			for (let i = array.length - 1; console.log(i) || i > 0; i--) {
				//array.length-1
				let r = Math.floor(Math.random() * retArray.length);
				//let p = Math.floor(Math.random() * (array.length + 1));
				/*let tmp = array[i];
                array[i] = array[r];
                array[r] = tmp;*/
				[retArray[i], retArray[r]] = [retArray[r], retArray[i]];
			}
			console.log(retArray);
			return retArray;
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
.predict {
	width: 100%;
	/* width: 300px; */
	word-wrap: break-word;
}
p {
	color: #00000099;
}

v-avatar {
	border-radius: 50%;
}

.red {
	background-color: blue;
	/* background-color: #ffcdd2; */
}

.blue {
	background-color: #b3e5fc;
}

.green {
	background-color: #c8e6c9;
}

.purple {
	background-color: #e1bee7;
}

.yellow {
	background-color: #fff9c4;
}

.orange {
	background-color: #ffccbc;
}

.brown {
	background-color: #d7ccc8;
}

.blue-grey {
	background-color: #cfd8dc;
}

.white {
	background-color: white;
}
#timer {
	font-family: "Avenir", Helvetica, Arial, sans-serif;
	-webkit-font-smoothing: antialiased;
	-moz-osx-font-smoothing: grayscale;
	text-align: center;
	color: #2c3e50;
	margin-top: 80px;
	margin-bottom: 20px;
	display: flex;
	flex-direction: column;
	justify-content: center;
	align-items: center;
	width: 100%;
}
</style>
