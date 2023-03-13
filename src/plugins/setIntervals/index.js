export default {
    install (vue) {
    vue.prototype.$intervals = []
    vue.prototype.$setInterval = (func, intervalMilliSec) => {
        const id = setInterval(() => {
        func()
        }, intervalMilliSec)
        vue.prototype.$intervals.push(id)
        return id
    }
    vue.prototype.$clearInterval = (id) => {
        clearInterval(id)
        vue.prototype.$intervals = vue.prototype.$intervals.filter(i => i !== id)
    }
    vue.prototype.$clearAllIntervals = () => {
        vue.prototype.$intervals.forEach(clearInterval)
        vue.prototype.$intervals = []
    }
    }
}