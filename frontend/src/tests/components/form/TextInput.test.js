/**
 * @vitest-environment happy-dom
 */

import { describe, it, expect } from "vitest";
import { mount } from "@vue/test-utils";
import TextInput from "@/components/form/TextInput.vue";

describe("TextInput component tests", () => {
  it("should render input elements without a label", () => {
    const wrapper = mount(TextInput, {
      props: {
        label: "",
        modelValue: "",
        type: "input"
      },
    });

    expect(wrapper.find("label").exists()).toBeFalsy();
    expect(wrapper.find("input").exists()).toBeTruthy();
  });

  it("emits input value when changed", async () => {
    const wrapper = mount(TextInput, {
      props: {
        modelValue: "",
        type: "input"
      },
    });

    await wrapper.find("input").setValue("New Test Value");
    
    const emittedEvents = wrapper.emitted("update:modelValue");
    expect(emittedEvents).toBeTruthy();
    expect(emittedEvents[0]).toEqual(["New Test Value"]);
  });

  it("should render input elements with a label", () => {
    const wrapper = mount(TextInput, {
      props: {
        label: "Input label",
        modelValue: "",
        type: "input"
      },
    });

    expect(wrapper.find("label").exists()).toBeTruthy();
  });
});
