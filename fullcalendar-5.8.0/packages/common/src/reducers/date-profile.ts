import { DateProfile, DateProfileGenerator } from '../DateProfileGenerator'
import { Action } from './Action'
import { DateMarker } from '../datelib/marker'

export function reduceDateProfile(
  currentDateProfile: DateProfile | null,
  action: Action,
  currentDate: DateMarker,
  dateProfileGenerator: DateProfileGenerator,
): DateProfile {
  let dp: DateProfile

  switch (action.type) {
    case 'CHANGE_VIEW_TYPE':
      return dateProfileGenerator.build(action.dateMarker || currentDate)

    case 'CHANGE_DATE':
      return dateProfileGenerator.build(action.dateMarker)

    case 'PREV':
      dp = dateProfileGenerator.buildPrev(currentDateProfile, currentDate)
      if (dp.isValid) {
        return dp
      }
      break

    case 'NEXT':
      dp = dateProfileGenerator.buildNext(currentDateProfile, currentDate)
      if (dp.isValid) {
        return dp
      }
      break
  }

  return currentDateProfile
}
