<template>
	<div class="home">
		<div class="headSpace"></div>
		<!--v-row justify="end">
        <v-btn @click="wayOfPlayLink" class="ma-2" text icon color="blue lighten-2">
            <v-icon dark>
                mdi-help-circle-outline
            </v-icon>
        </v-btn>
    </v-row-->

		<div class="modal">
			<h1>{{ msg }}</h1>
			<v-row justify="end">
				<v-btn
					@click="showModal()"
					class="ma-5"
					text
					icon
					color="blue lighten-2"
				>
					<v-icon dark>
						mdi-help-circle-outline
					</v-icon>
				</v-btn>
				<modal
					:message="message"
					v-show="modal"
					@execute-method="executeMethod"
				></modal>
			</v-row>
		</div>

		<div>
			<v-alert
				v-if="showAlert"
				:value="alert"
				color="pink"
				dark
				border="top"
				icon="mdi-alert-octagon"
				transition="scale-transition"
			>
				ログインしてください
			</v-alert>
		</div>

		<v-row justify="center" class="my-6">
			<v-btn color="primary" outlined @click="goWait()">
				<v-icon dark left>mdi-home-plus </v-icon>
				部屋作成
			</v-btn>
		</v-row>
		<!-- <v-row justify="center" class="my-6">
			<v-btn color="brown lighten-5" @click="goTest()"
				>テストページ（praise,guess確認用）</v-btn
			>
		</v-row> -->

		<div class="text-center">
			<v-btn
				color="error"
				outlined
				@click="$store.state.user.displayName ? qrcode() : (alert = true)"
			>
				<v-icon dark left>mdi-qrcode-scan </v-icon>
				QR読み込み
			</v-btn>
			<v-dialog v-model="dialog" width="500">
				<v-card>
					<v-card-title class="text-h5 grey lighten-2">
						ここにQRコードをうつしてね
					</v-card-title>

					<v-card-text>
						<div>
							<p class="decode-result">
								Last result: <b>{{ result }}</b>
							</p>
							<qrcode-stream :camera="camera" @decode="onDecode" @init="onInit">
								<div
									v-show="showScanConfirmation"
									class="scan-confirmation"
								></div>
							</qrcode-stream>
						</div>
					</v-card-text>

					<v-divider></v-divider>

					<v-card-actions>
						<v-spacer></v-spacer>
						<v-btn
							color="teal darken-1"
							text
							@click="(dialog = false), (camera = 'off')"
						>
							閉じる
						</v-btn>
					</v-card-actions>
				</v-card>
			</v-dialog>
		</div>

		<v-row justify="center" class="my-6">
			<v-btn color="success" outlined @click="goCollection()">
				<v-icon dark left>mdi-folder-heart </v-icon>
				過去の褒め一覧
			</v-btn>
		</v-row>
	</div>
</template>

<script>
// @ is an alias to /src
// import { mapGetters } from "vuex";

import axios from "axios";
/*
モーダルウィンドウで遊び方出せたらいいな，その2
*/
import Modal from "../components/HowTo.vue";

export default {
	name: "Home",
	/*
    モーダルウィンドウで遊び方出せたらいいな，その3
    */
	components: {
		Modal,
	},

	data() {
		return {
			roomID: null,
			host: false,
			alert: false,
			showAlert: false,
			user: this.$store.state.user,
			dialog: false,
			camera: "auto",
			result: null,
			showScanConfirmation: false,
			memberID: null,
			r: "",
			message: [
				"Googleアカウントでログインします。",
				"ゲームでホストをやる人は、QRコードを作成し、メンバーを集めます。",
				"ホスト以外の参加メンバーは、ホストのスマホに表示されるQRコードを読み取り、部屋に入ります。",
				"メンバーが揃ったらホストがゲームを開始します。",
				"ランダムに褒める人が割り振られます。1人あたり最大4人に対して褒めてください。",
				"褒め終わると、ある人が4人に対して褒めた言葉が出てくるので、誰に対して書いた褒め言葉なのか予想してください。",
				"全ての予想が終わると、答え合わせの時間になり、答え合わせも一通り終わるとゲームは終了です。",
				"これまで誰かが誉めてくれた言葉は保存されます。",
			],
			modal: false,
			num: 0,
		};
	},
	props: {
		msg: String,
	},
	created() {
		console.log("ユーザ情報", this.$store.state.user.displayName);
		console.log("なんで", this.$store.state.user);
		this.showAlert = !this.$store.getters.isLoggedIn;
	},
	// computed: {
	// ...mapGetters(["isLoggedIn"]),
	// },
	mounted() {
		this.$store.watch(
			(state, getters) => getters.isLoggedIn,
			(newValue, oldValue) => {
				console.log("prefecture changed! %s => %s", oldValue, newValue);
				this.alert = newValue;
				this.showAlert = newValue == false;
			}
		);
	},
	watch: {
		result(newValue) {
			console.log(newValue);
			this.$store.commit("setRoomID", {
				roomID: newValue,
			});
			//this.$store.commit("setRoomID", { roomID: 'wispt4u3rjexe5iz'});
			axios
				.get(
					`https://ryota.nkmr.io/m1_hackathon_2022/get_room_host.php?room_id=${newValue}`,
					{}
				)
				.then((res) => {
					console.log(res.data[0].host_id);
					this.fetchRoomID(
						newValue,
						res.data[0].host_id,
						this.$store.state.user.uid
					);
					//真ん中をホストを導くやつにする
					this.$router.push(`/wait/${this.$store.state.roomID}`);
				});
		},
	},
	methods: {
		async onInit(promise) {
			try {
				await promise;
			} catch (e) {
				console.error(e);
			} finally {
				this.showScanConfirmation = this.camera === "off";
			}
		},

		async onDecode(content) {
			this.result = content;

			this.pause();
			await this.timeout(500);
			this.unpause();
		},

		showModal() {
			// モーダル表示する際の処理が必要ならここに書く
			this.modal = true;
			this.num = 0;
		},

		executeMethod(yes) {
			// モーダルを非表示にして、モーダルでの選択結果によって処理を変える
			this.modal = false;
			if (yes) {
				//alert("はい が押されました。");
			} else {
				//alert("いいえ が押されました。");
			}
		},

		unpause() {
			this.camera = "auto";
		},

		pause() {
			this.camera = "off";
		},

		timeout(ms) {
			return new Promise((resolve) => {
				window.setTimeout(resolve, ms);
			});
		},
		goWait() {
			if (this.$store.state.user.displayName == null) {
				// this.alert = false;
				console.log("ユーザ情報", this.$store.state.user.displayName);
				this.alert = true;
			} else {
				this.$store.commit("setHost", {
					host: true,
				});
				//room_tableにインサート

				this.getRoomID();
				console.log(this.roomID);
				this.fetchRoomID(
					this.$store.state.roomID,
					this.$store.state.user.uid,
					this.$store.state.user.uid
				);

				this.$router.push(`/wait/${this.$store.state.roomID}`);
				//roomIDの部屋に入る
			}
		},
		goTest() {
			//デバッグ用！！！！！！！！！！消せ！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！
			if (this.$store.state.user.displayName == null) {
				// this.alert = false;
				console.log("ユーザ情報", this.$store.state.user.displayName);
				this.alert = true;
			} else {
				//this.getRoomID();
				this.$store.commit("setHost", {
					host: true,
				});
				//インサートのテスト
				//roomIDの部屋に入る
				//this.fetchRoomID();
				this.$store.commit("setRoomID", {
					roomID: "wispt4u3rjexe5iz",
				});
				this.$router.push(`/test/wispt4u3rjexe5iz`);
			}
		},
		getRoomID() {
			// 生成する文字列の長さ

			var l = 16;
			// 生成する文字列に含める文字セット
			var c = "abcdefghijklmnopqrstuvwxyz0123456789";
			var cl = c.length;
			var r = "";
			for (var i = 0; i < l; i++) {
				r += c[Math.floor(Math.random() * cl)];
			}

			//this.$store.commit("setRoomID", { roomID: 'wispt4u3rjexe5iz'});
			//this.roomID = 'wispt4u3rjexe5iz';
			//this.$store.commit("setRoomID", { roomID: 'aSEugVcKa3623gQY'});
			//this.roomID = 'aSEugVcKa3623gQY';
			//上はテスト用に全部同じ部屋に入るようにしたやつ

			this.$store.commit("setRoomID", {
				roomID: r,
			});
			console.log("rID", this.$store.state.roomID);
			//this.roomID = this.$store.state.roomID;

			//↑↑↑↑↑↑本番はこっち↑↑↑↑↑↑↑
		},
		qrcode() {
			this.dialog = true;
			this.camera = "auto";
		},
		goCollection() {
			if (this.$store.state.user.displayName == null) {
				// this.alert = false;
				console.log("ユーザ情報", this.$store.state.user.displayName);
				this.alert = true;
			} else {
				this.getRoomID();
				//this.$store.commit("setHost", { host: true });
				//collectionの部屋に入る
				this.$router.push(`/collection`);
			}
		},
		fetchRoomID(room_id, host_id, user_id) {
			//生成したルームID
			//部屋作成した人をホストとする
			//状態はwaitingで
			console.log(
				`https://ryota.nkmr.io/m1_hackathon_2022/insert_room_table.php?room_id=${room_id}&host_id=${host_id}&user_id=${user_id}&status=waiting`
			);
			fetch(
				`https://ryota.nkmr.io/m1_hackathon_2022/insert_room_table.php?room_id=${room_id}&host_id=${host_id}&user_id=${user_id}&status=waiting`
			);
		},
		wayOfPlayLink() {
			//nkmr研スクボの遊び方ページに飛ぶ
			//nkmr研の人しか見れない笑
			const url =
				"https://scrapbox.io/nkmr-lab/homehome_%E9%81%8A%E3%81%B3%E6%96%B9";
			window.location.href = url;
		},
	},
};
</script>

<style scoped>
.headSpace {
	height: 3rem;
}
.scan-confirmation {
	position: absolute;
	width: 100%;
	height: 100%;

	background-color: rgba(255, 255, 255, 0.8);

	display: flex;
	flex-flow: row nowrap;
	justify-content: center;
}
</style>
