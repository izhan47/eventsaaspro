<template>
  <div>
    <stripe-element-card
      ref="elementRef"
      :pk="stripe_key"
      @token="tokenCreated"
    />
    <!-- <button @click="submit" class="mt-3">Generate token</button> -->
  </div>
</template>

<script>
import { StripeElementCard } from '@vue-stripe/vue-stripe';
export default {
  components: {
    StripeElementCard,
  },
  props: [ 'stripe_key' ],
  data () {
    this.publishableKey = this.stripe_key;
    return {
      token: null,
    };
  },
  methods: {
    submit () {
      // this will trigger the process
      this.$refs.elementRef.submit();
    },
    tokenCreated (token) {
      // handle the token
      this.$emit('stripeToken', token);
      // send it to your server
    },
  }
};
</script>