if (typeof globalThis.structuredClone === 'undefined') {
    globalThis.structuredClone = function(obj) {
      return JSON.parse(JSON.stringify(obj));
    };
  }
  