<script>

export default {
    data() {
        return {
            backgrounds: [
                'assets/img/balay_san_nicolas.jpg', 
                'assets/img/Niyogyugan_Festival.jpg', 
                'assets/img/visit-bantay-bell-tower-1.jpg',
                //add more
            ],
            currentBackground: '',
        };
    },
    //currently set into updating the background EVERY MINUTE
    //change now.getDate(); to make it change EVERY MONTH
    //newMinute -> newDay and currentMinute() -> currentDay()
    computed: {
        currentMinute() {
            const now = new Date();
            return now.getMinutes(); // Get the current minute of the hour
        }
    },
    watch: {
        currentMinute(newMinute) {
        // Update the background image every minute
        this.currentBackground = this.backgrounds[newMinute % this.backgrounds.length];
        }
    },
    mounted() {
        // Set the initial background image when the component is mounted
        this.currentBackground = window.appUrl + "/" + this.backgrounds[this.currentMinute % this.backgrounds.length];
        console.log('Initial background:', this.currentBackground);
    }
};
</script>

<template>
    <div class="flex-grow flex items-start relative px-20" 
        :style="{ backgroundImage: `url(${currentBackground})`, backgroundSize: 'cover',
        backgroundPosition: 'center',  }">

        <div
            class="font-public-sans h-screen w-full max-w-lg px-10 py-10 bg-midnight-express sm:rounded-lg relative"
        >
            <div class="flex justify-center items-center">
                <img
                    class="w-64"
                    src="/assets/img/talapamana_logo.png"
                    alt=""
                />
            </div>
            
            <slot />

            <div class="text-center absolute bottom-5 left-0 right-0">
                <p class="text-sm text-hawkes-blue">Copyright 2023</p>
            </div>
        </div>
        
    </div>
</template>

