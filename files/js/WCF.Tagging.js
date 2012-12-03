/**
 * Namespace for tagging related functions.
 */
WCF.Tagging = {};

/**
 * Editable tag list.
 * 
 * @see	WCF.EditableItemList
 */
WCF.Tagging.TagList = WCF.EditableItemList.extend({
	/**
	 * @see	WCF.EditableItemList._className
	 */
	_className: 'wcf\\data\\tag\\TagAction',
	
	/**
	 * @see	WCF.EditableItemList.init()
	 */
	init: function(itemListSelector, searchInputSelector) {
		this._allowCustomInput = true;
		
		this._super(itemListSelector, searchInputSelector);
		
		this._data = [ ];
		this._search = new WCF.Tagging.TagSearch(this._searchInput, $.proxy(this.addItem, this));
	},
	
	/**
	 * @see	WCF.EditableItemList._submit()
	 */
	_submit: function() {
		this._super();
		
		for (var $i = 0, $length = this._data.length; $i < $length; $i++) {
			// deleting items leaves crappy indices
			if (this._data[$i]) {
				$('<input type="hidden" name="tags[]" value="' + this._data[$i] + '" />').appendTo(this._form);
			}
		};
	},
	
	/**
	 * @see	WCF.EditableItemList._addItem()
	 */
	_addItem: function(objectID, label) {
		this._data.push(label);
	},
	
	/**
	 * @see	WCF.EditableItemList._removeItem()
	 */
	_removeItem: function(objectID, label) {
		for (var $i = 0, $length = this._data.length; $i < $length; $i++) {
			if (this._data[$i] === label) {
				delete this._data[$i];
				return;
			}
		}
	},
	
	/**
	 * @see	WCF.EditableItemList.load()
	 */
	load: function(data) {
		if (data && data.length) {
			for (var $i = 0, $length = data.length; $i < $length; $i++) {
				this.addItem({ objectID: 0, label: data[$i] });
			}
		}
	}
});

/**
 * Search handler for tags.
 * 
 * @see	WCF.Search.Base
 */
WCF.Tagging.TagSearch = WCF.Search.Base.extend({
	/**
	 * @see	WCF.Search.Base._className
	 */
	_className: 'wcf\\data\\tag\\TagAction',
	
	/**
	 * @see	WCF.Search.Base.init()
	 */
	init: function(searchInput, callback, excludedSearchValues, commaSeperated) {
		this._super(searchInput, callback, excludedSearchValues, commaSeperated, false);
	}
});