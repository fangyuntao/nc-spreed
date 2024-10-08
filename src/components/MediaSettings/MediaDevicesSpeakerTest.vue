<!--
  - SPDX-FileCopyrightText: 2024 Nextcloud GmbH and Nextcloud contributors
  - SPDX-License-Identifier: AGPL-3.0-or-later
-->

<template>
	<div class="media-devices-checker">
		<VolumeHighIcon class="media-devices-checker__icon" :size="16" />
		<NcButton type="secondary" @click="playTestSound">
			{{ buttonLabel }}
		</NcButton>
		<div v-if="isPlayingTestSound" class="equalizer">
			<div v-for="bar in equalizerBars"
				:key="bar.key"
				class="equalizer__bar"
				:style="bar.style" />
		</div>
	</div>
</template>

<script>
import VolumeHighIcon from 'vue-material-design-icons/VolumeHigh.vue'

import { t } from '@nextcloud/l10n'

import NcButton from '@nextcloud/vue/dist/Components/NcButton.js'

import { useSoundsStore } from '../../stores/sounds.js'

export default {

	name: 'MediaDevicesSpeakerTest',

	components: {
		NcButton,
		VolumeHighIcon,
	},

	setup() {
		return {
			soundsStore: useSoundsStore()
		}
	},

	data() {
		return {
			isPlayingTestSound: false,
		}
	},

	computed: {
		buttonLabel() {
			return this.isPlayingTestSound
				// TRANSLATORS Playing the test sound to check speakers
				? t('spreed', 'Playing …')
				: t('spreed', 'Test speakers')
		},

		equalizerBars() {
			return Array.from(Array(4).keys()).map(item => ({
				key: item,
				style: {
					height: Math.random() * 100 + '%',
					animationDelay: Math.random() * -2 + 's',
				},
			}))
		}
	},

	methods: {
		t,

		playTestSound() {
			if (this.isPlayingTestSound) {
				this.soundsStore.pauseAudio('wait')
				return
			}
			this.isPlayingTestSound = true
			try {
				this.soundsStore.playAudio('wait')
				this.soundsStore.audioObjects.wait.addEventListener('ended', () => {
					this.isPlayingTestSound = false
				}, { once: true })
			} catch (error) {
				console.error(error)
				this.isPlayingTestSound = false
			}
		},
	},
}
</script>

<style lang="scss" scoped>
.media-devices-checker {
	display: flex;
	gap: var(--default-grid-baseline);
	margin: calc(3 * var(--default-grid-baseline)) 0;

	&__icon {
		display: flex;
		justify-content: center;
		align-items: center;
		width: var(--default-clickable-area);
		flex-shrink: 0;
	}

	.equalizer {
		margin-left: 8px;
		height: var(--default-clickable-area);
		display: flex;
		align-items: center;

		&__bar {
			width: 8px;
			height: 100%;
			background: var(--color-primary-element);
			border-radius: 4px;
			margin: 0 2px;
			will-change: height;
			animation: equalizer 2s steps(15, end) infinite;
		}
	}
}

@keyframes equalizer {
	@for $i from 0 through 15 {
		#{4*$i}% { height: random(70) + 20%; }
	}
}
</style>
