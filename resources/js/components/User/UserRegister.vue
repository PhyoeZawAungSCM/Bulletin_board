<template>
    <div class="row justify-content-center">
        <div class="col-9">
            <div class="card mt-5 border-success">
                <div class="card-header bg-success">
                    <h3 class="text-white">Register</h3>
                </div>
                <div
                    class="card-body justify-content-center d-block m-auto"
                    style="width: 600px"
                >
                    <ValidationObserver v-slot="{ handleSubmit }">
                        <form
                            @submit.prevent="
                                isConfirm
                                    ? handleSubmit(Confirm)
                                    : handleSubmit(Register)
                            "
                        >
                            <div class="row mb-3 align-items-center">
                                <div class="col-4 text-end">
                                    <label for="name">Name</label>
                                    <span class="text-danger">*</span>
                                </div>
                                <div class="col-8">
                                    <ValidationProvider
                                        rules="required"
                                        v-slot="{ errors }"
                                    >
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="name"
                                            v-model="user.name"
                                        />
                                        <span class="invalid-feedback">{{
                                            errors[0]
                                        }}</span>
                                    </ValidationProvider>
                                </div>
                            </div>
                            <!-- Name input -->
                            <div class="row mb-3 align-items-center">
                                <div class="col-4 text-end">
                                    <label for="email">E-Mail Address</label>
                                    <span class="text-danger">*</span>
                                </div>
                                <div class="col-8">
                                    <ValidationProvider
                                        rules="required|email|max:50"
                                        v-slot="{ errors }"
                                    >
                                        <input
                                            type="email"
                                            class="form-control"
                                            id="email"
                                            v-model="user.email"
                                        />
                                        <span class="invalid-feedback">{{
                                            errors[0]
                                        }}</span>
                                    </ValidationProvider>
                                </div>
                            </div>
                            <!-- Email input -->
                            <div class="row mb-3 align-items-center">
                                <div class="col-4 text-end">
                                    <label for="password">Password</label>
                                    <span class="text-danger">*</span>
                                </div>
                                <div class="col-8">
                                    <ValidationProvider
                                        rules="required|min:6|max:50"
                                        v-slot="{ errors }"
                                        vid="passwordConfirm"
                                    >
                                        <input
                                            type="password"
                                            class="form-control"
                                            id="password"
                                            v-model="user.password"
                                        />
                                        <span class="invalid-feedback">{{
                                            errors[0]
                                        }}</span>
                                    </ValidationProvider>
                                </div>
                            </div>
                            <!-- password input -->
                            <div class="row mb-3 align-items-center">
                                <div class="col-4 text-end">
                                    <label for="passwordConfirm"
                                        >Password confirmation</label
                                    >
                                    <span class="text-danger">*</span>
                                </div>
                                <div class="col-8">
                                    <ValidationProvider
                                        rules="required|min:6|max:50|confirm:passwordConfirm"
                                        v-slot="{ errors }"
                                    >
                                        <input
                                            type="password"
                                            class="form-control"
                                            id="passwordConfirm"
                                            v-model="user.confirmPassword"
                                        />
                                        <span class="invalid-feedback">{{
                                            errors[0]
                                        }}</span>
                                    </ValidationProvider>
                                </div>
                            </div>
                            <!-- password confirm input -->
                            <div class="row mb-3 align-items-center">
                                <div class="col-4 text-end">
                                    <label for="type">Type</label>
                                    <span class="text-danger">*</span>
                                </div>
                                <div class="col-8">
                                    <ValidationProvider
                                        rules="required"
                                        v-slot="{ errors }"
                                    >
                                        <select
                                            class="form-select"
                                            aria-label="Default select example"
                                            id="type"
                                            v-model="user.type"
                                        >
                                            <option
                                                v-if="
                                                    $store.state.auth.loginUser
                                                        .type == '0'
                                                "
                                                value="admin"
                                                selected
                                            >
                                                Admin
                                            </option>
                                            <option value="user">User</option>
                                        </select>
                                        <span class="invalid-feedback">{{
                                            errors[0]
                                        }}</span>
                                    </ValidationProvider>
                                </div>
                            </div>
                            <!-- type input -->
                            <div class="row mb-3 align-items-center">
                                <div class="col-4 text-end">
                                    <label for="phone">Phone</label>
                                </div>
                                <div class="col-8">
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="phone"
                                        v-model="user.phone"
                                    />
                                </div>
                            </div>
                            <!-- phone input -->
                            <div class="row mb-3 align-items-center">
                                <div class="col-4 text-end">
                                    <label for="dob">Date of birth</label>
                                </div>
                                <div class="col-8">
                                    <input
                                        type="date"
                                        class="form-control"
                                        id="dob"
                                        v-model="user.dob"
                                    />
                                </div>
                            </div>
                            <!-- date of birth input -->
                            <div class="row mb-3 align-items-center">
                                <div class="col-4 text-end">
                                    <label for="address">Address</label>
                                    <span class="text-danger">*</span>
                                </div>
                                <div class="col-8">
                                    <ValidationProvider
                                        rules="required"
                                        v-slot="{ errors }"
                                    >
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="address"
                                            v-model="user.address"
                                        />
                                        <span class="invalid-feedback">{{
                                            errors[0]
                                        }}</span>
                                    </ValidationProvider>
                                </div>
                            </div>
                            <!-- Address input -->
                            <div class="row mb-3 align-items-center">
                                <div class="col-4 text-end">
                                    <label for="profile">Profile</label>
                                    <span class="text-danger">*</span>
                                </div>
                                <div class="col-8">
                                    <div class="mb-3" v-if="isConfirm">
                                        <img
                                            :src="previewImage"
                                            alt="..."
                                            style="width: 100px"
                                        />
                                    </div>
                                    <div class="mb-3" v-else>
                                        <ValidationProvider
                                            rules="required|image|size:2048"
                                            ref="provider"
                                            v-slot="{ errors }"
                                        >
                                            <input
                                                class="form-control"
                                                type="file"
                                                id="profile"
                                                @change="uploadData"
                                                ref="profile"
                                            />
                                            <span class="invalid-feedback">{{
                                                errors[0]
                                            }}</span>
                                        </ValidationProvider>
                                    </div>
                                </div>
                            </div>
                            <!-- profile input -->
                            <div
                                v-if="isConfirm"
                                class="row justify-content-end"
                            >
                                <div class="col-8">
                                    <button
                                        class="btn btn-primary"
                                        type="submit"
                                    >
                                        Confirm
                                    </button>
                                    <button
                                        class="btn btn-secondary"
                                        @click="cancel"
                                    >
                                        Cancel
                                    </button>
                                </div>
                            </div>
                            <div v-else class="row justify-content-end">
                                <div class="col-8">
                                    <button
                                        class="btn btn-primary"
                                        type="submit"
                                    >
                                        Register
                                    </button>
                                    <button
                                        class="btn btn-secondary"
                                        @click="clear"
                                    >
                                        Clear
                                    </button>
                                </div>
                            </div>
                        </form>
                        <!-- end of form -->
                    </ValidationObserver>
                    <!-- ValidationObserver -->
                </div>
                <!-- end of card body -->
            </div>
        </div>
    </div>
</template>

<script>
import { disableFormInputs } from "../../services/DisableInput";
export default {
    data() {
        return {
            user: {
                name: "",
                email: "",
                password: "",
                confirmPassword: "",
                type: "",
                phone: "",
                dob: "",
                address: "",
                profile: null,
            },
            previewImage: null,
        };
    },
    methods: {
        Register() {
            console.log("Register");
            this.$store.commit("SET_CREATED_TEMP_USER", this.user);
            this.$router.push("/register-confirm");
        },
        Confirm() {
            this.$store.dispatch("registerUser");
        },
        async uploadData(e) {
            const { valid } = await this.$refs.provider.validate(e);
            if (valid) {
                this.user.profile = this.$refs.profile.files.item(0);
                console.log("Image is ready to upload to backend");
            }
        },
        clear() {
            this.user = {
                name: "",
                email: "",
                password: "",
                confirmPassword: "",
                type: "",
                phone: "",
                dob: "",
                address: "",
                profile: "",
            };
        },
        cancel() {
            this.$router.push("/register");
        },
    },
    props: {
        isConfirm: Boolean,
    },
    mounted() {
        this.user = this.$store.state.user.createdTempUser;
        if (this.isConfirm) {
            disableFormInputs();
            this.previewImage = URL.createObjectURL(this.user.profile);
            console.log("Confirm mode");
        }
    },
};
</script>

<style></style>
