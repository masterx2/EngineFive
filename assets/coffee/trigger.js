// Generated by CoffeeScript 1.9.3
var Arrays, Logger, Module, Queue, Strings, Trigger,
  indexOf = [].indexOf || function(item) { for (var i = 0, l = this.length; i < l; i++) { if (i in this && this[i] === item) return i; } return -1; },
  extend = function(child, parent) { for (var key in parent) { if (hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; },
  hasProp = {}.hasOwnProperty;

Module = (function() {
  function Module() {}

  Module.moduleKeywords = ['extended', 'included'];

  Module.extend = function(obj) {
    var key, ref, value;
    for (key in obj) {
      value = obj[key];
      if (indexOf.call(this.moduleKeywords, key) < 0) {
        this[key] = value;
      }
    }
    if ((ref = obj.extended) != null) {
      ref.apply(this);
    }
    return this;
  };

  Module.include = function(obj) {
    var key, ref, value;
    for (key in obj) {
      value = obj[key];
      if (indexOf.call(this.moduleKeywords, key) < 0) {
        this.prototype[key] = value;
      }
    }
    if ((ref = obj.included) != null) {
      ref.apply(this);
    }
    return this;
  };

  return Module;

})();

Arrays = {
  object2array: function(args) {
    var i, item, len, results;
    if (args && args.length > 0) {
      results = [];
      for (i = 0, len = args.length; i < len; i++) {
        item = args[i];
        results.push(item);
      }
      return results;
    }
  }
};

Strings = {
  pad: function(string, width, fill) {
    if (string == null) {
      string = '';
    }
    if (width == null) {
      width = 4;
    }
    if (fill == null) {
      fill = ' ';
    }
    if (string.length < width) {
      return string + new Array(width - string.length + 1).join(fill);
    } else {
      return string;
    }
  }
};

Logger = (function(superClass) {
  extend(Logger, superClass);

  function Logger() {
    return Logger.__super__.constructor.apply(this, arguments);
  }

  Logger.include(Arrays);

  Logger.include(Strings);

  Logger.prototype.format = function(level, _args) {
    var args;
    args = this.object2array(_args);
    args.unshift(this.pad('[' + level + '] ' + (new Date()).getTime() / 1000, 14));
    return args;
  };

  Logger.prototype.log = function() {
    var args;
    args = this.format('debug', arguments);
    return Function.prototype.apply.call(window.console.log, window.console, args);
  };

  Logger.prototype.error = function() {
    var args;
    args = this.format('error', arguments);
    return Function.prototype.apply.call(window.console.error, window.console, args);
  };

  return Logger;

})(Module);

Queue = (function() {
  function Queue() {
    this.queue = [];
  }

  Queue.prototype.hasNext = function() {
    return this.queue.length > 0;
  };

  Queue.prototype.enqueue = function(item) {
    if (item != null) {
      return this.queue.unshift(item);
    }
  };

  Queue.prototype.next = function() {
    return this.queue.pop();
  };

  return Queue;

})();

Trigger = (function(superClass) {
  extend(Trigger, superClass);

  function Trigger() {
    this.events = {};
  }

  Trigger.prototype._bind = function(name, callback, once) {
    var base;
    if ((base = this.events)[name] == null) {
      base[name] = new Queue();
    }
    if (typeof callback === 'function') {
      return this.events[name].enqueue({
        callback: callback,
        once: once
      });
    }
  };

  Trigger.prototype.bind = function(name, callback) {
    return this._bind(name, callback, false);
  };

  Trigger.prototype.bindonce = function(name, callback) {
    return this._bind(name, callback, true);
  };

  Trigger.prototype.unbind = function(name) {
    if (indexOf.call(Object.keys(this.events), name) >= 0) {
      return delete this.events[name];
    }
  };

  Trigger.prototype.execute = function() {
    if (!arguments.length) {
      this.error("[trigger] Wrong trigger.execute call");
      return false;
    }
    return this.trigger.apply(this, arguments);
  };

  Trigger.prototype.trigger = function(name) {
    var _queue, args, queue, task;
    if (!name) {
      this.error("[trigger] Name must be defined");
      return false;
    }
    if (indexOf.call(Object.keys(this.events), name) < 0) {
      this.error("[trigger] Has no listeners for event: " + name);
      return false;
    }
    this.log("[trigger] Executing event: " + name);
    args = this.object2array(arguments);
    queue = this.events[name];
    _queue = new Queue();
    while (queue.hasNext()) {
      task = queue.next();
      task.callback.apply(window, args);
      if (!task.once) {
        _queue.enqueue(task);
      }
    }
    return this.events[name] = _queue;
  };

  return Trigger;

})(Logger);

//# sourceMappingURL=trigger.js.map
