<template>
<div class="Test">
    <br />
    <br />
    <br />
    <h1>test.vueに来たよ</h1>
    <v-row justify="end">
	<v-btn @click="dialog=true" 
		fab
        dark
        fixed
        bottom
        right
        color="red lighten-2">
			<v-icon dark>
				mdi-qrcode-plus
			</v-icon>
            <!-- QR表示 -->
        </v-btn>
	</v-row>

    <div class="text-center ma-6">
        <v-dialog v-model="dialog_thema" width="500" height="500">
            <v-card>
                <v-card-title>
                    褒め合うテーマを決めよう！
                </v-card-title>
                <br />
                <!-- <select v-model="select_thema" class="mx-5 px-2 py-2" width="700" outlined>
                    <option disabled value="" >テーマを選択してください</option>
                    <option>研究のことで褒め合う</option>
                    <option>日常のことで褒め合う</option>
                    <option>間接的に聞いたことで褒め合う</option>
                </select> -->
                <!-- {{select_thema}} -->
                <v-select :items="thema" outlined label="テーマを選択" class="mx-5" color="amber lighten-1" item-color="amber lighten-1" v-model="select_thema">
                </v-select>
                <br />
                <!-- <v-textarea outlined class="mx-5" label="自由にテーマを入力" value="typing thema" color="amber lighten-1" v-model="freetext_thema">
                </v-textarea>
                <br /> -->
                <v-btn-toggle
                    v-model="select_thema"
                    color="amber lighten-1"
                    dense
                >
                <v-btn class="mx-8" value="テーマは設定しないで自由に褒め合う！">テーマは設定しないで自由に褒め合う！</v-btn>
                </v-btn-toggle>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn :disabled="existSelectThema" color="amber lighten-1" text @click="(dialog_thema = false)">
                        次へ
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <!-- <v-btn @click="dialog=true">
            QR表示
        </v-btn> -->
        <v-dialog v-model="dialog" width="500">
            <v-card>
                <v-card-title class="text-h5 grey lighten-2">
                    部屋へ入るQRコード
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
        <h2 class="mx-2">{{select_thema}}</h2>
        <br />
        <v-btn @click="dialog_thema=true">テーマ変更</v-btn>
    </div>

    <br />
    <h2 class="ml-6">🌟メンバー🌟</h2>

    <!------------------- メンバーをうまいこと表示したい ------------------>
    <v-row justify="center" class="my-3" cols="3" v-for="(memberPhotoURL, index) in memberPhotoURL" :key="index">
        <v-expand-x-transition>
            <v-card width="200" :color="colorList[index]" transition="slide-y-transition">
                <v-avatar>
                    <v-img :src="memberPhotoURL" contain max-width="40" height="40"></v-img>
                </v-avatar>
                {{ memberName[index] }}
            </v-card>
        </v-expand-x-transition>
        <!-- </v-col> -->
    </v-row>
    <!------------------------ ここまで -------------------------->

    <v-row justify="center" class="my-12">
        <v-btn v-if="host === true" @click="goPraise()">スタート</v-btn>
        <v-btn @click="backHome()">back</v-btn>
    </v-row>
    <div>
        <v-alert v-if="alertSelect" :value="alert" color="pink" dark border="top" icon="mdi-alert-octagon" transition="scale-transition">
            テーマが選択されていません！

        </v-alert>
    </div>

</div>
</template>

<script>
import axios from 'axios';
export default {
    name: "TestWait",
    components: {},
    data() {
        return {
            //memberはroomTableからとってくる
            memberPhotoURL: [],
            memberName: [],
            memberID: [],
            member: [],
            colorList: [
                "red lighten-4",
                "light-blue lighten-4",
                "green lighten-4",
                "purple lighten-4",
                "yellow lighten-4",
                "deep-orange lighten-4",
                "brown lighten-4",
            ],
            select_thema: '',
            thema: [
                '研究のことで褒め合う！',
                '日常のことで褒め合う！',
                '間接的に聞いたことで褒め合う！',
                '性格のことで褒め合う！',
            ],
            memberColor: [],
            //確かめ用
            members: ["0", "1", "2", "3", "4"],
            toMembersList: [],
            memberIndex: [],
            //member: ["ueki", "yukino", "komatsuna", "ikumin", "kojiro"],
            roomID: this.$store.state.roomID,
            host: this.$store.state.host,
            user: this.$store.state.user,
            dialog: false,
            dialog_thema: true,
            alertFew: false,
            alertSelect: false,
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

        this.$setInterval(() => {
            //定期的に実行
            this.getUpdate();
            console.log("test dayo")
        }, 1000)

        console.log("memberID", this.memberID)
        console.log("name", this.memberName)
        console.log("photoURL", this.memberPhotoURL)
        // this.getUserInfo();
        console.log("最初のメンバーズ：", this.members)
        //this.toMembersList.push(this.random_select4(this.members))
        // this.random_select4(this.members);
        console.log("toMembersList", this.toMembersList[0])

    },
    mounted() {
        // this.getUserInfo();
    },
    computed: {
        roomLink: function () {
            return this.roomID;
        },
        existSelectThema: function () {
			if(this.select_thema==''){
				return true
			}else{
            return false;
			}
        },
    },
    methods: {
        async setMemberIndex() {
            for (var i = 0; i < this.memberID.length; i++) {
                this.memberIndex.push(i)
            }
            console.log("memberIndex", this.memberIndex)
        },
        goPraise() {
            if (this.memberID.length < 5) {
                console.log("人数が足りません");
                this.alertFew = true;
            } else if(this.select_thema === '') {
                console.log("テーマが選択されていません");
                this.alertSelect = true;
            } else {
                if(this.host){
                    console.log("makeActive",this.roomID,this.user.uid,this.user.uid);
                    //fetch(`https://ryota.nkmr.io/m1_hackathon_2022/insert_room_table.php?room_id=${this.roomID}&host_id=${this.user.uid}&user_id=${this.user.uid}&status=active`)
                }
                //ゲームスタートを押した時点でのメンバーをストアに放り込む

                this.setMemberIndex();
                console.log("memberIndex", this.memberIndex)
                this.toMembersList.push(this.random_select4(this.memberIndex))
                this.$store.commit("setMember", this.member);
                console.log("member", this.member);
                this.$store.commit("setMemberName", this.memberName);
                this.$store.commit("setMemberPhoto", this.memberPhotoURL);
                this.$store.commit("setMemberID", this.memberID);
                this.setColor();
                this.$store.commit("setToMembersList", {
                    toMembersList: this.toMembersList[0]
                });
                this.$clearAllIntervals();

                this.$router.push(`/praise/${this.$store.state.roomID}`);
                this.$store.commit("setSelectThema", this.select_thema);
            }
        },
        backHome() {
            console.log("backHome")
            this.$clearAllIntervals();
            window.location.href = `..#/`
        },
        /*
        getIsRoomActive() {
            //room_tableを見て，その部屋がactiveかを判断
            //res.data.status=true=>募集中,false=>入れないよ
            axios.get(`https://ryota.nkmr.io/m1_hackathon_2022/json_output.php?host_id=${this.roomID}`, {}).then(res => {
                console.log(this.roomID)
                console.log(res.data)
                console.log(res.data.status)
            });
        },
        */
        async getWhoWithHost() {
            //同じ部屋にいる人のidを取得
            return axios.get(`https://ryota.nkmr.io/m1_hackathon_2022/get_room_users.php?room_id=${this.roomID}`, {}).then(res => {
                //return axios.get(`https://ryota.nkmr.io/m1_hackathon_2022/get_room_users.php?room_id=${this.roomID}`, {}).then(res => {
                console.log(JSON.stringify(res.data))
                this.member = res.data
                console.log("this.memberの中身:", this.member[0].user_id)
                //host_idとuser_idが帰ってくる配列をそのままmemberに入れているよ
                //console.log(JSON.stringify(res.data.user_id))
                //console.log(this.member)
            });
            // for(var i=0; i<this.member.length;i++){
            // 	this.memberID.push(this.member[i].user_id)
            // }
        },
        async getUpdate() {
            //新しい人が入ってきたかを確認する
            return axios.get(`https://ryota.nkmr.io/m1_hackathon_2022/get_room_users.php?room_id=${this.roomID}`, {}).then(res => {
                console.log(res.data);
                this.getNewMember(res.data);
                console.log(res.data[0].host_id)
                this.getIsRoomActive(res.data[0].host_id)
            });
        },
        async getIsRoomActive(hostID) {
            //room_tableを見て，その部屋がactiveかを判断
            //res.data.status=true=>募集中,false=>入れないよ
            console.log(hostID);
            return axios.get(`https://ryota.nkmr.io/m1_hackathon_2022/json_output.php?host_id=${hostID}`, {}).then(res => {
                console.log(res.data.status);
                if(res.data.status==true){
                    //roomがactiveになった時，ゲストたちもPraiseに移行する
                    this.goPraise();
                }
            });
        },
        async getNewMember(response) {
            //新しい人が入ってきたかを確認する
            console.log(response);
            if (response.length !== this.member.length) {
                //増えたかの判定
                console.log(response, this.member);
                var l = this.member.length;
                for (let i = 0; i < response.length - l; i++) {
                    //console.log("newMember",res.data[this.member.length+i]);
                    console.log(response[l + i]);
                    this.member = response;
                    //増えた人数分だけ新しくpushする

                }
                //console.log("pushedMember",this.member)
                this.checkMember();
                //新しいメンバーで名前や写真を更新する
            }
        },
        /*
        async getNewMember() {
            //新しい人が入ってきたかを確認する
            return axios.get(`https://ryota.nkmr.io/m1_hackathon_2022_komatsubara/get_room_users.php?room_id=${this.roomID}`, {}).then(res => {
                if (res.data.length != this.member.length) {
                    //増えたかの判定
                    console.log("hueta");
                    var l = this.member.length;
                    for (let i = 0; res.data.length - l > i; i++) {
                        //console.log("newMember",res.data[this.member.length+i]);

                        this.member.push(res.data[l + i]);
                        //増えた人数分だけ新しくpushする

                    }
                    //console.log("pushedMember",this.member)
                    this.checkMember();
                    //新しいメンバーで名前や写真を更新する
                }
                //console.log("data",res.data);
                //console.log("member",this.member)
                //console.log(res.data[res.data.length-1].user_id);
            });
        },
        */
        async getUserName(m_id) {
            console.log("m_id", m_id);
            return axios.get(`https://ryota.nkmr.io/m1_hackathon_2022/user_json.php?uid=${m_id}`, {}).then(res => {
                console.log("res", res);
                return res.data[0].displayName
            })
        },
        async getUserPhoto(m_id) {
            return axios.get(`https://ryota.nkmr.io/m1_hackathon_2022/user_json.php?uid=${m_id}`, {}).then(res => {
                return res.data[0].photoURL;
            })
        },
        async checkMember() {
            //this.member = [];
            this.memberID = [];
            this.memberName = [];
            this.memberPhotoURL = [];
            console.log("chekmember", this.member)
            //とりまテストでstoreのメンバーに私の情報4人分入れる
            for (var i = 0; i < this.member.length; i++) {
                //this.$store.commit("setMember",適当なid)
                this.memberID.push(this.member[i].user_id);
                //console.log("メンバーID",this.memberID);
                console.log(await this.getUserPhoto(this.memberID[i]));
                this.memberName.push(await this.getUserName(this.memberID[i]));
                this.memberPhotoURL.push(await this.getUserPhoto(this.memberID[i]));
            }
            console.log("メンバーID", this.memberID);
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
            let members_count = [];
            let result_random = {};
            let praise_count = [];

            for (let i = 0; i < arrayData.length; i++) {
                members_count[i] = arrayData[i];
            }
            for (let i = 0; i < 4; i++) {
                praise_count[i] = members_count;
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
                while (flag == 0) {

                    console.log(mawaru);
                    mawaru++;
                    praise_count[i] = shuffle(praise_count[i]);
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
                    color: this.colorList[i]
                })
            }
            this.$store.commit("setMemberColor", {
                memberColor: this.memberColor
            });
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
}
</script>

<style scoped></style>
