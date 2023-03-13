<template>
  <div class="parent">
    <div class="base-timer">
      <svg class="base-timer__svg" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
        <g class="base-timer__circle">
          <circle class="base-timer__path-elapsed" cx="50" cy="50" r="45"></circle>
          <path
            :stroke-dasharray="circleDasharray"
            class="base-timer__path-remaining"
            :class="remainingPathColor"
            d="
              M 50, 50
              m -45, 0
              a 45,45 0 1,0 90,0
              a 45,45 0 1,0 -90,0
            "
          ></path>
        </g>
      </svg>
      <span class="base-timer__label">{{ formattedTimeLeft }}</span>
    </div>

    <div class="alert">
			<v-alert
      v-show="alert"
				color="pink"
				dark
        dense
				border="left"
				icon="mdi-alert-octagon"
				transition="scale-transition"
			>
				はやくして！！
			</v-alert>
		</div>
  </div>
  </template>
  
  <script>
  const FULL_DASH_ARRAY = 283;
  const WARNING_THRESHOLD = 10;
  const ALERT_THRESHOLD = 5;
  
  const COLOR_CODES = {
    info: {
      color: "green"
    },
    warning: {
      color: "orange",
      threshold: WARNING_THRESHOLD
    },
    alert: {
      color: "red",
      threshold: ALERT_THRESHOLD
    }
  };
  
//   const TIME_LIMIT = 60;

  
  export default {
    data() {
      return {
        timePassed: 0,
        timerInterval: null,
        alert: false,
      };
    },
    props: {
    timeLimit: {
      type: Number,
      required: true,
    },
  },
//   props: [
//     "timeLimit"
//   ],
    computed: {
      circleDasharray() {
        return `${(this.timeFraction * FULL_DASH_ARRAY).toFixed(0)} 283`;
      },
  
      formattedTimeLeft() {
        const timeLeft = this.timeLeft;
        const minutes = Math.floor(timeLeft / 60);
        let seconds = timeLeft % 60;
  
        if (seconds < 10) {
          seconds = `0${seconds}`;
        }
  
        return `${minutes}:${seconds}`;
      },
  
      timeLeft() {
        return this.timeLimit - this.timePassed;
      },
  
      timeFraction() {
        const rawTimeFraction = this.timeLeft / this.timeLimit;
        return rawTimeFraction - (1 / this.timeLimit) * (1 - rawTimeFraction);
      },
  
      remainingPathColor() {
        const { alert, warning, info } = COLOR_CODES;
  
        if (this.timeLeft <= alert.threshold) {
          return alert.color;
        } else if (this.timeLeft <= warning.threshold) {
          return warning.color;
        } else {
          return info.color;
        }
      }
    },
  
    watch: {
      timeLeft(newValue) {
        if (newValue === 0) {
          this.onTimesUp();
          // this.timeLimit=10
          // this.startTimer();
          // this.resetTimer();
          this.$emit('timeup')
          this.alert=true;
        }
      }
    },
  
    mounted() {
      this.startTimer();
    },
  
    methods: {
      onTimesUp() {
        clearInterval(this.timerInterval);
      },
      startTimer() {
        this.timerInterval = setInterval(() => (this.timePassed += 1), 1000);
      },
      resetTimer(){
        this.alert = false
        clearInterval(this.timerInterval);
        // this.timerInterval=null
        this.timePassed = 0
        this.startTimer()
      },
    }
  };
  </script>
  
  <style scoped lang="scss">
.parent{
  position: relative;
  width: 200px;
  height: 140px;
}
.alert {
  position: relative;
  // position: absolute;
  top: 105px;
  width: 200px;
  height: 40px;
}
.base-timer {
    position: absolute;
    width: 100px;
    height: 100px;
    left: 0;
    right: 0;
    margin: auto;
  
    &__svg {
      transform: scaleX(-1);
    }
  
    &__circle {
      fill: none;
      stroke: none;
    }
  
    &__path-elapsed {
      stroke-width: 7px;
      stroke: grey;
    }
  
    &__path-remaining {
      stroke-width: 7px;
      stroke-linecap: round;
      transform: rotate(90deg);
      transform-origin: center;
      transition: 1s linear all;
      fill-rule: nonzero;
      stroke: currentColor;
  
      &.green {
        color: rgb(65, 184, 131);
      }
  
      &.orange {
        color: orange;
      }
  
      &.red {
        color: red;
      }
    }
  
    &__label {
      position: absolute;
      width: 100px;
      height: 100px;
      top: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
    }
  }
  </style>
  