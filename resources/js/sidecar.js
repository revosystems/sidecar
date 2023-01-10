const debounce = (callback, wait = 200) => {
    let timeoutId = null
    return (...args) => {
      window.clearTimeout(timeoutId)
      timeoutId = window.setTimeout(() => callback.apply(null, args), wait)
    }
  }

class SidecarSelector {
    static selector(element, placeholder) {
        return new Choices(element, {
            placeholderValue: placeholder,
            removeItemButton: true,
            allowHTML: true,
            itemSelectText: '',
            classNames: {
                containerInner: 'sidecar-choices-inner',
                input: 'sidecar-choices-input',
                item: 'choices__item sidecar-choices-item',
                button: 'choices__button sidecar-choices-button',
            }
        })
    }

    static fetchSelector(element, placeholder, url) {
        const choice = new Choices(element, {
            placeholderValue: placeholder,
            removeItemButton: true,
            allowHTML: true,
            itemSelectText: '',
            searchFloor: 10,
            classNames: {
                containerInner: 'sidecar-choices-inner',
                input: 'sidecar-choices-input',
                item: 'choices__item sidecar-choices-item',
                button: 'choices__button sidecar-choices-button',
            },
            callbackOnInit: async function(){ SidecarSelector.searchChoices(this, '', url)}
        })

        choice.passedElement.element.parentNode.querySelector('input').addEventListener(
            'input',
            debounce((event) => SidecarSelector.searchChoices(choice, event.target.value, url)),
        )

        choice.passedElement.element.addEventListener(
            'search',
            (event) => {
                event.preventDefault()
                event.stopPropagation()
            },
            'true',
        )
    }

    static searchChoices(choice, search, url) {
        choice.setChoices(async () => {
            try {
                const response = await fetch(`${url}?search=${search}`)
                const results = await response.json()
                let choices = []
                for (let key in results) {
                    if (results.hasOwnProperty(key)) {
                        choices.push({
                            value: results[key]['id'],
                            label: results[key]['name']
                        })
                    }
                }
                return choices
            } catch (err) {
                console.error(err)
            }
        },
        'value',
        'label',
        true,
        ).then(() => choice.passedElement.element.parentNode.querySelector('input').focus())
    }
}

class SidecarHtmlLoader {
    static load(url, target) {
        fetch(url)
            .then(response => response.text())
            .then(data => {
                let element = document.getElementById(target)
                element.innerHTML = data
                const scripts = element.getElementsByTagName('script')
                for (let n = 0; n < scripts.length; n++)
                    eval(scripts[n].innerHTML)
            })
    }
}