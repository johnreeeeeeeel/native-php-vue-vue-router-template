<template>
  <div class="toast-container position-fixed top-0 end-0 p-3">
    <div ref="toastEl" class="toast align-items-center border-0" :class="toastClass" role="alert" aria-live="assertive" aria-atomic="true">
      
      <div class="d-flex">
        <div class="toast-body">
          {{ message }}
        </div>

        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" ></button>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref } from "vue"
import * as bootstrap from "bootstrap"

const toastEl = ref(null)
const message = ref("")
const toastClass = ref("text-bg-success")

const showToast = (msg, type = "success") => {
  message.value = msg

  toastClass.value = type === "success" ? "text-bg-success" : "text-bg-danger"
  const toast = new bootstrap.Toast(toastEl.value)

  toast.show()
}

defineExpose({
  show: showToast
})
</script>