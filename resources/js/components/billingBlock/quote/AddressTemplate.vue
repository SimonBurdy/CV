<template>
  <div>
    <b>{{item.name}}</b>
  </div>
</template>

<script>
export default {
  name:"AddressTemplate",
  props: {
    item: { required: true },
    searchText: { required: true }
  }
}
</script>