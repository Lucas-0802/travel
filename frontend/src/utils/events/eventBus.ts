import { reactive } from "vue";

type EventNames = "refreshDashboard";

type EventCallbacks = {
  refreshDashboard: () => void;
  refreshTable: () => void;
};

export const eventBus = reactive({    
  events: {} as Record<EventNames, Function[]>,

  emit<T extends EventNames>(event: T, ...args: Parameters<EventCallbacks[T]>) {
    if (this.events[event]) {
      this.events[event].forEach(callback => callback(...args));
    }
  },

  on<T extends EventNames>(event: T, callback: EventCallbacks[T]) {
    if (!this.events[event]) {
      this.events[event] = [];
    }
    this.events[event].push(callback);
  },

  off<T extends EventNames>(event: T, callback: EventCallbacks[T]) {
    if (this.events[event]) {
      this.events[event] = this.events[event].filter(cb => cb !== callback);
    }
  }
});
