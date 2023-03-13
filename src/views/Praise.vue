<template>
	<div class="praise">
		<div class="headSpace"></div>
		<h2 class="text-center">{{ select_thema }}</h2>
		<div id="timer">
			<Timer ref="timer" :timeLimit="60" />
		</div>

		<h4 class="text-center">あなたが褒める4人は</h4>
		<v-row justify="center">
			<v-col cols="5" v-for="index in 4" :key="index">
				<v-card
					:width="wWidth / 2"
					:color="colorList[myToMemberIndex[index - 1]]"
				>
					<v-row>
						<v-col cols="4">
							<v-avatar>
								<v-img
									:src="memberPhotoURL[myToMemberIndex[index - 1]]"
									contain
									max-width="40"
									height="40"
								></v-img>
							</v-avatar>
						</v-col>
						<v-col cols="8">
							{{ memberName[myToMemberIndex[index - 1]] }}
						</v-col>
					</v-row>
				</v-card>
			</v-col>
		</v-row>

		<v-row justify="center" class="mt-6">
			<div class="please text-center">
				今は
				<v-avatar>
					<v-img
						:src="memberPhotoURL[myToMemberIndex[num]]"
						contain
						max-width="40"
						height="40"
					></v-img> </v-avatar
				>"{{ memberName[myToMemberIndex[num]] }}"を褒めて！！！！
			</div>
		</v-row>

		<v-row justify="center">
			<div>
				<v-textarea
					solo
					name="input-7-4"
					v-model="praiseWord"
					label="ほめて！"
				></v-textarea>
			</div>
		</v-row>

		<v-row justify="center" class="mb-6">
			<div>
				<v-btn
					:disabled="existPraiseWord"
					outlined
					color="teal lighten-3"
					class="font-weight-bold"
					@click="toNext()"
				>
					<v-icon dark left>
						mdi-chevron-right-circle
					</v-icon>
					{{ nextMessage }}
				</v-btn>
			</div>
		</v-row>
	</div>
</template>

<script>
// @ is an alias to /src
import Timer from "../components/Timer";
import axios from "axios";

export default {
	name: "Praise",
	components: {
		Timer,
	},
	data() {
		return {
			wWidth: window.innerWidth, //ウィンドウさいず
			wHeight: window.innerWidth,
			member: [],
			memberPhotoURL: [],
			memberName: [],
			roomID: this.$store.state.roomID,
			memberID: [],
			memberColor: this.$store.state.memberColor,
			toMembersList: this.$store.state.toMembersList,
			myToMemberIndex: [],
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
			nextMessage: "次の人をほめる",
			num: 0,
			praiseWord: "",
			select_thema: this.$store.state.select_thema,
			// timerReset: true,
		};
	},
	created() {
		console.log("メンバーカラー：", this.memberColor);
		// console.log("toMembersListは", this.$store.state.toMembersList)
		console.log("toMembersListは", this.toMembersList);
		console.log("ワタシハダレ", this.user.uid);
		this.getUserInfo();
		this.searchToMember();
		console.log(this.memberID);
		this.$setInterval(() => {
			//定期的に実行
			this.goGuess();
		}, 3000);
	},
	computed: {
		existPraiseWord: function() {
			if (this.praiseWord == "") {
				return true;
			} else {
				return false;
			}
		},
	},
	methods: {
		toNext() {
			/////////ここで褒め言葉をほめテーブルにインサートする///////////
			if (this.roomID != "wispt4u3rjexe5iz") {
				fetch(
					`https://ryota.nkmr.io/m1_hackathon_2022/insert_home_table.php?from_id=${
						this.user.uid
					}&to_id=${
						this.memberID[this.myToMemberIndex[this.num]]
					}&words_of_praise=${this.praiseWord}&room_id=${this.roomID}`
				);
			}

			//////======================================/////////////
			console.log(
				"From:",
				this.user.uid,
				", To:",
				this.memberID[this.myToMemberIndex[this.num]]
			);
			//console.log("From:",this.user.displayName,", To:",this.memberName[this.myToMemberIndex[this.num]])
			console.log("ほめことば:", this.praiseWord);
			console.log(this.roomID);
			console.log(this.num);
			this.num++;
			this.praiseWord = "";
			this.timerStart();
			if (this.num == 3) {
				this.nextMessage = "次は予測だよ！";
			}
			/*
			if(this.num==4){
				this.goGuess()
			}
			*/
		},
		timerStart() {
			this.$refs.timer.resetTimer();
		},
		searchToMember() {
			let myIndex = 0;
			for (var i = 0; i < this.memberID.length; i++) {
				if (this.user.uid == this.memberID[i]) {
					myIndex = i;
				}
			}
			this.myToMemberIndex = this.toMembersList[
				Object.keys(this.toMembersList).find((i) => i == myIndex)
			];
			console.log("myToMemberIndexは", this.myToMemberIndex);
			console.log("select_thema", this.select_thema);
		},
		async goGuess() {
			return axios
				.get(
					`https://ryota.nkmr.io/m1_hackathon_2022/get_praise_room_id.php?room_id=${this.roomID}`,
					{}
				)
				.then((res) => {
					console.log("num", this.num);
					console.log("res", res.data.length);
					console.log("member", this.member.length);
					if (this.num == 4 && res.data.length == this.member.length * 4) {
						this.$clearAllIntervals();
						this.$router.push(`/guess/${this.$store.state.roomID}`);
					}
				});
		},
		getUserInfo() {
			console.log(this.$store.state.member);
			console.log(this.$store.state.memberPhoto);
			console.log(this.$store.state.memberName);
			this.member = this.$store.state.member;
			this.memberPhotoURL = this.$store.state.memberPhoto;
			this.memberName = this.$store.state.memberName;
			this.memberID = this.$store.state.memberID;
			this.select_thema = this.$store.state.select_thema;
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
.please {
	/* width: 100%; */
	width: 300px;
	word-wrap: break-word;
}
.headSpace {
	height: 5rem;
}
#timer {
	font-family: "Avenir", Helvetica, Arial, sans-serif;
	-webkit-font-smoothing: antialiased;
	-moz-osx-font-smoothing: grayscale;
	text-align: center;
	color: #2c3e50;
	margin-top: 20px;
	margin-bottom: 20px;
	display: flex;
	flex-direction: column;
	justify-content: center;
	align-items: center;
	width: 100%;
}
</style>
