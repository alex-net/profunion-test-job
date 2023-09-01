new Vue({
    el: '#app',
    data: {
        theme: 'dark',
        panelCollapse: false,
        dateTime: 'xx.xx.xxxx<br/>xx:xx',
        info: {},
        indicators: 55,
        actions: [],
        isShowTablo: false

    },
    methods: {
        themeChange() {
            this.theme = this.theme == 'dark' ? '' : 'dark';
        }
    },
    computed: {
        isDark() {
            return this.theme == 'dark';
        }
    },
    created() {
        fetch('/data.json').then(ret => ret.json()).then(data => {
            for (k in data) {
                this.$set(this, k, data[k]);
            }
        });

        let addZerro = dig => {
            let v = dig.toString()
            return v.length == 1 ? '0' + v : v;
        }
        let dateUpd = () => {
            let date = new Date();
            this.dateTime = `${addZerro(date.getDate())}.${addZerro(date.getMonth()+1)}.${date.getFullYear()}<br>${addZerro(date.getHours())}:${addZerro(date.getMinutes())}`;
        }
        dateUpd();
        setInterval(dateUpd, 1000 * 60);
    },
    updated() {
        if (!this.isShowTablo && Object.entries(this.indicators).length > 0) {
            const pi2 = Math.PI/2;
            // console.log(this.indicators)
            for (let k in this.$refs) {
                if (this.$refs[k][0] == undefined)
                    continue;
                this.isShowTablo = true;
                let el = this.$refs[k][0];
                el.width = 100;
                el.height = 100;
                let cv = el.getContext('2d');
                cv.lineWidth = 10;
                cv.lineCap = 'round';
                cv.strokeStyle = '#' + this.indicators[k.substr(6)].color;
                cv.arc(50, 50, 45, -pi2, this.indicators[k.substr(6)].val / this.indicators[k.substr(6)].max * 2 * Math.PI - pi2);
                cv.stroke();
            }
        }
    },
})