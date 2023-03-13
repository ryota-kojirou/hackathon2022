<template>
	<div id="app">
		<v-app>
			<v-container>
			<v-app-bar
				absolute
				color="teal lighten-3"
				elevate-on-scrolls
				scroll-target="#scrolling-techniques-7"
			>
				<img
				src="@/assets/logo_HomeMee.png"
				height=60
				/>
				<v-spacer></v-spacer>
				<p class="white--text  font-weight-bold ma-2" justify="center">
					{{ $store.state.user.displayName }}
				</p>
				<v-avatar size="36" @click="userInfoCheck">
					<v-img :src="user.photoURL" contain></v-img>
				</v-avatar>
				<v-avatar v-show="!user.uid" key="logout" @click="login()">
					<span
						><v-icon>
							mdi-account-circle
						</v-icon></span
					>
				</v-avatar>
			</v-app-bar>
			<router-view />
		</v-container>
		</v-app>
	</div>
</template>

<script>
import {
	getAuth,
	onAuthStateChanged,
	signInWithPopup,
	GoogleAuthProvider,
	signOut,
} from "firebase/auth";
import axios from 'axios';

export default {
	name: "App",
	data() {
		return {
			user: [],
		};
	},
	created() {
		const auth = getAuth();
		onAuthStateChanged(auth, (user) => {
			this.$store.commit("setUser", { user: user ? user : {} });
			this.user = user ? user : {};
		});
		//   onAuthStateChanged(auth, (user) => {
		//     if (user) {
		//       this.$store.commit("setUser", {
		//         name: user.displayName,
		//         mail: user.email,
		//         image: user.photoURL
		//       });
		//       console.log("ログインしました");
		//     } else {
		//       this.$store.commit("setUser", null);
		//       console.log("ログアウトしました");
		//     }
		//     console.log(this.$store.state.userData);
		//   });
		// },
		// onAuthStateChanged(auth, (user) => {
		// 	this.$store.commit("setUser", { user: user ? user : {} });
		// 	this.user = user ? user : {};
		// });
	},
	methods: {
		userInfoCheck() {
			console.log(this.$store.state.user);
			this.logout();
		},
		login() {
			const provider = new GoogleAuthProvider();
			const auth = getAuth();
			signInWithPopup(auth, provider)
				.then((result) => {
					console.log(result);
					this.$store.commit("setUser", {
						user: result.user ? result.user : {},
					});
					return axios.get(`https://ryota.nkmr.io/m1_hackathon_2022/check_user_id.php?uid=${this.$store.state.user.uid}`).then(res=>{
						console.log(res.data)
						if(res.data == "0"){
							console.log("追加しました",res.data);
							fetch(`https://ryota.nkmr.io/m1_hackathon_2022/insert_user_table.php?uid=${this.$store.state.user.uid}&displayName=${this.$store.state.user.displayName}&photoURL=${this.$store.state.user.photoURL}`)
						}
					});
					
				})
				.catch((error) => {
					console.error(error);
				});
		},
		console() {
			// console("これこれ",this.$store.user);
		},
		logout() {
			const auth = getAuth();
			signOut(auth)
				.then(() => {
					// Sign-out successful.
					alert("サインアウトしました。");
				})
				.catch((error) => {
					// An error happened.
					console.error(error);
				});
			// // 関数の呼び出し方が違うので注意！
			// const auth = getAuth();
			// auth.signOut();
		},
	},
};
</script>

<style>
/* .v-img {
	width: 100%;
	height: 100%;
	object-fit: contain;
} */
p {
	display: table-cell;
	vertical-align: middle;
	text-align: center;
	/* color: black; */
}
</style>
