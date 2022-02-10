<template>
  <div>
    <b>{{item.ref}}</b>
    <span>{{ item.desc }} </span>
  </div>
</template>

<script>
export default {
  props: {
    item: { required: true },
    searchText: { required: true }
  }
}
</script>