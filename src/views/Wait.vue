<template>
	<div class="Wait">
		<div class="headSpace"></div>
		<v-row justify="end">
			<v-btn
				@click="dialog = true"
				fab
				dark
				fixed
				bottom
				right
				color="teal lighten-2"
			>
				<v-icon dark>
					mdi-qrcode-plus
				</v-icon>
				<!-- QR表示 -->
			</v-btn>
		</v-row>

		<div class="text-center ma-6">
			<v-dialog
				v-if="host === true"
				v-model="dialog_thema"
				width="500"
				height="500"
			>
				<v-card>
					<v-card-title class="py-6">
						褒め合うテーマを設定しよう！
					</v-card-title>
					<v-select
						:items="thema"
						outlined
						label="テーマを選択"
						class="mx-5"
						color="teal lighten-3"
						item-color="teal lighten-2"
						v-model="select_thema"
					>
					</v-select>
					<v-text-field
						class="mx-5"
						v-model="free_fill_thema"
						label="テーマを入力"
						outlined
						clearable
						color="teal lighten-3"
						maxlength="30"
					></v-text-field>
					<v-row justify="center">
						<!-- <v-btn-toggle v-model="select_thema" color="amber lighten-1" dense> -->
						<v-btn
							@click="
								select_thema = null;
								free_fill_thema = null;
							"
							>テーマは設定しない</v-btn
						>
						<!-- </v-btn-toggle> -->
					</v-row>
					<v-card-actions>
						<v-spacer></v-spacer>
						<v-btn color="teal darken-1" text @click="dialog_thema = false">
							次へ
						</v-btn>
					</v-card-actions>
				</v-card>
			</v-dialog>

			<v-dialog v-model="dialog" width="500">
				<v-card>
					<v-card-title class="text-h5 grey lighten-2">
						招待してね
					</v-card-title>
					<v-card-text class="pt-6">
						<v-row justify="center">
							<qriously :value="roomLink" :size="200" />
						</v-row>
					</v-card-text>

					<v-divider></v-divider>

					<v-card-actions>
						<v-spacer></v-spacer>
						<v-btn color="teal darken-1" text @click="dialog = false">
							閉じる
						</v-btn>
					</v-card-actions>
				</v-card>
			</v-dialog>
		</div>

		<div class="text-center">
			<h2
				v-if="this.free_fill_thema == null || this.free_fill_thema == ''"
				class="ma-3"
			>
				{{ select_thema }}
			</h2>
			<h2 v-else class="ma-3">{{ free_fill_thema }}</h2>
			<v-btn
				v-if="host === true"
				outlined
				color="teal lighten-3"
				class="font-weight-bold"
				@click="dialog_thema = true"
			>
				<v-icon dark left>mdi-comment-edit </v-icon>
				テーマ設定</v-btn
			>
		</div>
		<div class="py-3 mt-3">
			<h3>メンバー</h3>
		</div>

		<!------------------- メンバーをうまいこと表示したい ------------------>

		<v-row
			justify="center"
			class="my-3"
			v-for="(mPU, index) in memberID"
			:key="index"
		>
			<v-expand-x-transition>
				<v-card
					:width="wWidth / 2"
					:color="colorList[index]"
					transition="slide-y-transition"
				>
					<v-row>
						<v-col cols="3"
							><v-avatar>
								<v-img
									:src="memberPhotoURL[index]"
									contain
									max-width="40"
									height="40"
								></v-img>
							</v-avatar>
						</v-col>
						<v-col cols="9">
							<v-card-text>
								{{ memberName[index] }}
							</v-card-text>
						</v-col>
					</v-row>
				</v-card>
			</v-expand-x-transition>
		</v-row>
		<!------------------------ ここまで -------------------------->

		<v-row justify="center" class="my-12">
			<v-btn
				v-if="host === true"
				outlined
				color="teal lighten-3"
				class="font-weight-bold"
				@click="goPraise()"
			>
				<v-icon dark left>mdi-arrow-right-drop-circle </v-icon>
				スタート</v-btn
			>
		</v-row>
		<div>
			<!--v-alert v-if="alertSelect" :value="alert" color="pink" dark border="top" icon="mdi-alert-octagon" transition="scale-transition">
            テーマが選択されていません！

        </v-alert-->
		</div>
		<div>
			<v-alert
				v-if="alertFew"
				:value="alert"
				color="pink"
				dark
				border="top"
				icon="mdi-alert-octagon"
				transition="scale-transition"
			>
				人数が少ないです！
			</v-alert>
		</div>
	</div>
</template>

<script>
import axios from "axios";
export default {
	name: "Wait",
	components: {},
	data() {
		return {
			wWidth: window.innerWidth, //ウィンドウさいず
			wHeight: window.innerWidth,
			//memberはroomTableからとってくる
			memberPhotoURL: [],
			memberName: [],
			memberID: [],
			member: [],
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
			select_thema: "",
			free_fill_thema: null,
            thema: [
                "日常のことで褒め合う！",
				"研究のことで褒め合う！",
				"性格のことで褒め合う！",
			],
			memberColor: [],
			//確かめ用
			members: ["a", "b", "c", "d", "e", "f", "g", "h", "i"],
			toMembersList: [],
			memberIndex: [],
			//member: ["ueki", "yukino", "komatsuna", "ikumin", "kojiro"],
			roomID: this.$store.state.roomID,
			host: this.$store.state.host,
			user: this.$store.state.user,
			dialog: false,
			dialog_thema: false,
			alertFew: false,
		};
	},
	async created() {
		// makeCode();

		console.log("ユーザー情報", this.user.displayName);
		console.log("roomID:", this.roomID);
		console.log("host:", this.host);
		this.getIsRoomActive();
		await this.getWhoWithHost();
		await this.checkMember();
		console.log("memberID", this.memberID);
		console.log("name", this.memberName);
		console.log("photoURL", this.memberPhotoURL);
		await this.setMemberIndex();
		// this.getUserInfo();
		// this.toMembersList.push(this.random_select4(this.memberID))
		//
		// this.random_select4(this.members);
		console.log("toMembersList", this.toMembersList[0]);

		this.$setInterval(() => {
			//定期的に実行
			this.getUpdate();
		}, 3000);
	},
	mounted() {
		// this.getUserInfo();
	},
	computed: {
		roomLink: function() {
			return this.roomID;
		},
		// existSelectThema: function () {
		// 	if(this.free_fill_thema == ''){
		// 		return this.select_thema
		// 	}else{
		// 		return this.free_fill_thema
		// 	}
		// },
	},
	methods: {
		async setMemberIndex() {
			console.log(this.memberID.length);
			this.memberIndex = [];
			for (var i = 0; i < this.memberID.length; i++) {
				this.memberIndex.push(i);
			}
			console.log("memberIndex", this.memberIndex);
		},
		// themaChange(){
		// 	if(this.free_fill_thema==''){
		// 		return true
		// 	}else{
		// 		return false
		// 	}
		// },
		goPraise() {
			if (this.memberID.length < 5) {
				console.log("人数が足りません");
				this.alertFew = true;
			} else {
				if (this.host) {
					//ホストがゲーム開始を押したら
					//console.log("makeActive", this.roomID, this.user.uid, this.user.uid);
					fetch(
						`https://ryota.nkmr.io/m1_hackathon_2022/insert_room_table.php?room_id=${this.roomID}&host_id=${this.user.uid}&user_id=${this.user.uid}&status=active`
					);
					//該当の部屋をactiveにする
				}
				this.$clearAllIntervals();
				console.log(this.memberID);
				this.setMemberIndex();
				console.log(this.memberIndex);
				this.toMembersList.push(this.random_select4(this.memberIndex));
				//ゲームスタートを押した時点でのメンバーをストアに放り込む
				this.$store.commit("setMember", this.member);
				console.log("member", this.member);
				this.$store.commit("setMemberName", this.memberName);
				this.$store.commit("setMemberPhoto", this.memberPhotoURL);
				this.$store.commit("setMemberID", this.memberID);
				if (this.free_fill_thema == "" || this.free_fill_thema == null) {
					this.$store.commit("select_thema", this.select_thema);
				} else {
					this.$store.commit("select_thema", this.free_fill_thema);
				}
				console.log(this.$store.state.memberID);
				this.$router.push(`/praise/${this.$store.state.roomID}`);
				this.$store.commit("setToMembersList", {
					toMembersList: this.toMembersList[0],
				});
				this.setColor();
			}
		},
		async getWhoWithHost() {
			//同じ部屋にいる人のidを取得（少し未完成？）
			return axios
				.get(
					`https://ryota.nkmr.io/m1_hackathon_2022/get_room_users.php?room_id=${this.roomID}`,
					{}
				)
				.then((res) => {
					console.log(JSON.stringify(res.data));
					this.member = res.data;
					console.log("this.memberの中身:", this.member);
					//host_idとuser_idが帰ってくる配列をそのままmemberに入れているよ
					//console.log(JSON.stringify(res.data.user_id))
					//console.log(this.member)
				});
			// for(var i=0; i<this.member.length;i++){
			// 	this.memberID.push(this.member[i].user_id)
			// }
		},
		async getUserName(m_id) {
			return axios
				.get(
					`https://ryota.nkmr.io/m1_hackathon_2022/user_json.php?uid=${m_id}`,
					{}
				)
				.then((res) => {
					console.log(res);
					return res.data[0].displayName;
				});
		},
		async getUserPhoto(m_id) {
			return axios
				.get(
					`https://ryota.nkmr.io/m1_hackathon_2022/user_json.php?uid=${m_id}`,
					{}
				)
				.then((res) => {
					console.log(res.data[0].photoURL);
					return res.data[0].photoURL;
				});
		},
		async checkMember() {
			// this.member = [];
			//console.log();
			this.memberID = [];
			this.memberName = [];
			this.memberPhotoURL = [];
			console.log(this.member);
			console.log("length", this.member.length);
			for (var i = 0; i < this.member.length; i++) {
				this.memberID.push(this.member[i].user_id);
				console.log(this.memberID);
				console.log(await this.getUserPhoto(this.memberID[i]));
				this.memberName.push(await this.getUserName(this.memberID[i]));
				console.log("mn", this.memberName);
				this.memberPhotoURL.push(await this.getUserPhoto(this.memberID[i]));
				console.log("mP", this.memberPhotoURL);
				console.log("mPl", this.memberPhotoURL.length);
			}
		},
		async getUpdate() {
			//新しい人が入ってきたかを確認する
			return axios
				.get(
					`https://ryota.nkmr.io/m1_hackathon_2022/get_room_users.php?room_id=${this.roomID}`,
					{}
				)
				.then((res) => {
					console.log(res.data);
					this.getNewMember(res.data);
					console.log(res.data[0].host_id);
					this.getIsRoomActive(res.data[0].host_id);
				});
		},
		async getIsRoomActive(hostID) {
			//room_tableを見て，その部屋がactiveかを判断
			//res.data.status=true=>募集完了cc
			console.log(hostID);
			return axios
				.get(
					`https://ryota.nkmr.io/m1_hackathon_2022/json_output.php?host_id=${hostID}`,
					{}
				)
				.then((res) => {
					console.log(typeof res.data.status);
					if (res.data.status == "true") {
						//roomがactiveになった時，ゲストたちもPraiseに移行する
						console.log("goPraise");
						this.goPraise();
					}
				});
		},
		async getNewMember(response) {
			//新しい人が入ってきたかを確認する
			console.log(response);
			if (response.length > this.member.length) {
				//増えたかの判定
				console.log(response, this.member);
				//var l = this.member.length;
				this.member = response.concat();
				/*
                for (let i = 0; i < response.length - l; i++) {
                    //console.log("newMember",res.data[this.member.length+i]);
                    console.log(response[l + i]);
                    this.member = response;
                    //増えた人数分だけ新しくpushする

                }
                */
				//console.log("pushedMember",this.member)
				this.checkMember();
				//新しいメンバーで名前や写真を更新する
			}
		},
		check_double(arrayData, arrayData2) {
			for (let i = 0; i < arrayData.length; i++) {
				if (arrayData[i] == arrayData2[i]) {
					return true;
				}
			}
			return false;
		},
		random_select4(arrayData) {
			console.log("wao");
			if (arrayData.length < 4) return;
			let members_count = [];
			let result_random = {};
			let praise_count = [];

			for (let i = 0; i < arrayData.length; i++) {
				members_count[i] = arrayData[i];
			}
			for (let i = 0; i < 4; i++) {
				praise_count[i] = members_count.concat();
			}

			// どれとも被っていないこと，過去に自分が当たった人に当たらないこと
			for (let i = 0; i < 4; i++) {
				praise_count[i] = shuffle(praise_count[i]);

				let flag = 0;

				if (i == 0) {
					flag = 1;
				}

				// 元の状態と比較して一つでも一致していたらflag=0
				if (this.check_double(members_count, praise_count[i])) {
					flag = 0;
				} else {
					flag = 1;
				}

				// 過去のシャッフル結果と比較
				if (flag == 1) {
					for (let n = 0; n < i; n++) {
						if (this.check_double(praise_count[i], praise_count[n])) {
							flag = 0;
							break;
						} else {
							flag = 1;
						}
					}
				}
				var mawaru = 0;
				// console.log(check_double(members_count, praise_count[0]),  flag==0, praise_count[i]);
				while (flag == 0 && mawaru < 1000) {
					console.log(mawaru);
					mawaru++;
					praise_count[i] = shuffle(praise_count[i].concat());
					if (i == 0) {
						flag = 1;
					}
					// 元の状態と比較して一つでも一致していたらflag=0
					if (this.check_double(members_count, praise_count[i])) {
						flag = 0;
					} else {
						flag = 1;
					}

					// 過去のシャッフル結果と比較
					if (flag == 1) {
						for (let n = 0; n < i; n++) {
							if (this.check_double(praise_count[i], praise_count[n])) {
								flag = 0;
								break;
							} else {
								flag = 1;
							}
						}
					}
				}
			}
			// デバック用
			// console.log(praise_count);

			// 結果の整理をする
			for (let k = 0; k < arrayData.length; k++) {
				let temp = [];
				for (let v = 0; v < 4; v++) {
					temp.push(praise_count[v][k]);
				}
				result_random[arrayData[k]] = temp;
			}

			// 結果を返す(ここはよくわからないから合うようにいじってほしい)
			console.log(result_random);
			return result_random;
		},
		setColor() {
			for (var i = 0; i < this.memberID.length; i++) {
				this.memberColor.push({
					user_id: this.memberID[i],
					color: this.colorList[i],
				});
			}
			this.$store.commit("setMemberColor", {
				memberColor: this.memberColor,
			});
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

// 楽するために作った関数たち
const shuffle = ([...array]) => {
	for (let i = array.length - 1; i >= 0; i--) {
		const j = Math.floor(Math.random() * (i + 1));
		[array[i], array[j]] = [array[j], array[i]];
	}
	return array;
};
</script>

<style scoped>
select {
	border: 2px;
}
h2 {
	color: #37474f;
}
h3 {
	color: #3e2723;
	position: relative;
	padding-left: 25px;
}

h3:before {
	position: absolute;
	content: "";
	bottom: -3px;
	left: 0;
	width: 0;
	height: 0;
	border: none;
	border-left: solid 15px transparent;
	border-bottom: solid 15px #80cbc4;
}
h3:after {
	position: absolute;
	content: "";
	bottom: -3px;
	left: 10px;
	width: 50%;
	border-bottom: solid 3px #80cbc4;
}
.headSpace {
	height: 3rem;
}
</style>
