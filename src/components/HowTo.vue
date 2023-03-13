<template>
<div id="modal">
    <div id="modal-content" class="modal">

        <p id="modal-message" class="modal__message"><span>{{ message[num] }}</span></p>
        <span>

            <button class="modal__btn" @click="returnPage()">
                戻る
            </button>
            <button class="modal__btn" @click="nextPage()">次へ</button>
        </span>

        <p class="modal__pages">{{num+1}}/{{message.length}}</p>
    </div>
    <div id="modal-overlay"></div>
</div>
</template>

<script>
export default {
    name: "HowTo",
    props: ["message"],
    data() {
        return {
            num: 0,
        };
    },
    methods: {
        returnFalse() {
            this.$emit("execute-method", false);
        },
        returnTrue() {
            this.$emit("execute-method", true);
        },
        nextPage() {
            if (this.num < this.message.length - 1) {
                this.num++;
            } else {
                this.$emit("execute-method", true);
                this.num = 0;
            }
        },
        returnPage() {
            if (this.num > 0) {
                this.num--;
            } else {
                this.$emit("execute-method", false);
            }
        },
    },
};
</script>

<style scoped>
.modal {
    padding: 10px 20px;
    border: 6px solid #91C9C4;
    background: #F2ECCB;
    z-index: 2;
    display: block;
    text-align: center;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    border-radius: 10px;
}

.modal__message {
    margin-top: 10px;
    width: 90%;
}

.modal__cancel {
    margin-right: 30px;
    font-size: 15px;
}

.modal__cancel:hover {
    cursor: pointer;
    color: rgb(14, 48, 240);
    font-weight: bold;
}

.modal__btn {
    display: inline-block;
    margin: 30px auto;
    text-decoration: none;
    width: 40px;
    height: 30px;
    text-decoration: none;
    color: #000000;
    border: solid 2px #91C9C4;
    border-radius: 3px;
    transition: 0.4s;
    text-align: center;
    vertical-align: middle;
    font-size: 15px;
    background-color: #F2ECCB;
}

.modal__btn:hover {
    background: #6CAFC4;
    color: white;
    cursor: pointer;
}

#modal-overlay {
    z-index: 1;
    display: block;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 120%;
    background-color: rgba(0, 0, 0, 0.75);
}

.modal__pages {
    width: 20px;
}

</style>
